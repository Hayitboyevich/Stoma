<?php

namespace common\models;

use common\models\query\InvoiceQuery;
use common\models\traits\Invoice\HasRelationships;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int|null $patient_id
 * @property int|null $doctor_id
 * @property int|null $reception_id
 * @property string|null $comments
 * @property string|null $insurance_name Страховая компания
 * @property int|null $preliminary
 * @property int|null $assistant_id
 * @property int $discount
 * @property string $invoice_number Счет №
 * @property string $created_at Дата счета
 * @property int $status
 * @property int $type
 * @property string $closed_at Дата закрытия
 *
 * @property User $doctor
 * @property InvoiceServices[] $invoiceServices
 * @property Patient $patient
 * @property Reception $reception
 */
class Invoice extends ActiveRecord
{
    use HasRelationships;

    public const STATUS_PAID = 1; // оплачен
    public const STATUS_UNPAID = 0; // не оплачен

    public const PRELIMINARY = 1; // предварительный
    public const NOT_PRELIMINARY = 0; // не предварительный

    public const TYPE_NEW = 0; // новый
    public const TYPE_CLOSED = 1; // закрытый
    public const TYPE_DEBT = 2; // долг
    public const TYPE_INSURANCE = 3; // страховой
    public const TYPE_CANCELLED = 4; // аннулированный

    public const TYPE_ENUMERATION = 5; // перечисление

    public const TYPES = [
        self::TYPE_NEW => 'Новый',
        self::TYPE_CLOSED => 'Закрытый',
        self::TYPE_DEBT => 'Долг',
        self::TYPE_INSURANCE => 'Страховой',
        self::TYPE_CANCELLED => 'Аннулированный',
        self::TYPE_ENUMERATION => 'Перечисление'
    ];

    public const TYPES_COLOR = [
        self::TYPE_NEW => '#0047F9',
        self::TYPE_CLOSED => '#06D25C',
        self::TYPE_DEBT => '#DA1E28',
        self::TYPE_INSURANCE => '#A56EFF',
        self::TYPE_CANCELLED => '#FF9C06',
        self::TYPE_ENUMERATION => '#1f2128'
    ];

    public const STATUSES = [
        self::STATUS_PAID => 'Оплачен',
        self::STATUS_UNPAID => 'Не оплачен'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'patient_id',
                    'doctor_id',
                    'reception_id',
                    'preliminary',
                    'assistant_id',
                    'discount',
                    'status',
                    'type'
                ],
                'integer'
            ],
            [['invoice_number'], 'required'],
            [['created_at', 'closed_at'], 'safe'],
            [['comments', 'invoice_number', 'insurance_name'], 'string', 'max' => 255],
            [
                ['doctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['doctor_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::class,
                'targetAttribute' => ['patient_id' => 'id']
            ],
            [
                ['reception_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Reception::class,
                'targetAttribute' => ['reception_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'patient_id' => 'Patient ID',
            'doctor_id' => 'Doctor ID',
            'reception_id' => 'Reception ID',
            'assistant_id' => 'Reception ID',
            'comments' => 'Comments',
            'preliminary' => 'Предварительный',
            'invoice_number' => 'Счет №',
            'created_at' => 'Дата счета',
            'status' => 'Статус',
        ];
    }

    public static function find(): InvoiceQuery
    {
        return new InvoiceQuery(static::class);
    }

    public function getInvoiceTotal()
    {
        return InvoiceServices::find()
            ->select(['COALESCE(SUM(amount * price_with_discount * teeth_amount), 0) AS total'])
            ->where(['invoice_id' => $this->id])
            ->asArray()
            ->scalar();
    }

    public function getInvoiceTotalWithoutDiscount()
    {
        return InvoiceServices::find()
            ->select(['COALESCE(SUM(amount * price * teeth_amount), 0) AS total'])
            ->where(['invoice_id' => $this->id])
            ->asArray()
            ->scalar();
    }

    public function totalAfterDiscount()
    {
        $total = $this->getInvoiceTotal();
        return $this->patient->discount > 0 ? $total * (100 - $this->patient->discount) / 100 : $total;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber(): string
    {
        return "#{$this->id}";
    }

    /**
     * @return string|null
     */
    public function getVisit(): ?string
    {
        if ($this->reception) {
            return date('d.m.Y H:i', strtotime("{$this->reception->record_date} {$this->reception->record_time_from}"));
        }

        return null;
    }

    public function getInvoicePayTotal()
    {
        $transactions = Transaction::find()
            ->where(['invoice_id' => $this->id, 'type' => Transaction::TYPE_PAY])
            ->sum('amount');

        return is_null($transactions) ? 0 : $transactions;
    }

    public function getRemains()
    {
        return $this->getInvoiceTotal() - $this->getInvoicePayTotal();
    }

    public function getAllTeeth()
    {
        $result = InvoiceServices::find()
            ->select(['GROUP_CONCAT(DISTINCT teeth) AS all_teeth'])
            ->where(['invoice_id' => $this->id])
            ->groupBy('invoice_id')
            ->asArray()
            ->one();

        return $result ? $result['all_teeth'] : '';
    }

    public function calculateEmployeeSalary($userId, $amount): array
    {
        if (!in_array($userId, [$this->doctor_id, $this->assistant_id], true)) {
            return [
                'status' => 'fail',
                'message' => 'Сотрудник не участвует в приеме'
            ];
        }

        foreach ($this->invoiceServices as $is) {
            $dp = DoctorItemPercent::find()
                ->where(['doctor_id' => $userId, 'price_list_item_id' => $is->price_list_item_id])
                ->one();

            if (!isset($dp)) {
                $dp = DoctorPercent::findOne([
                    'user_id' => $userId,
                    'price_list_id' => $is->priceListItem->price_list_id
                ]);
                if (!$dp) {
                    return [
                        'status' => 'fail',
                        'message' => 'Не найден процент для сотрудника'
                    ];
                }
            }

            $invoiceTotal = $this->getInvoiceTotal();
            $price = $is->teeth_amount * $is->price_with_discount * $is->amount;
            $priceListPayPrice = $amount / $invoiceTotal * $price;
            $consumable = ($is->priceListItem->consumable * $is->amount * $is->teeth_amount)
                / $price * $priceListPayPrice;

            $employeeEarnings = ($priceListPayPrice - $consumable) * $dp->percent / 100;

            $es = new EmployeeSalary();
            $es->invoice_id = $this->id;
            $es->invoice_total = $invoiceTotal;
            $es->reception_id = $this->reception_id;
            $es->visit_time = "{$this->reception->record_date} {$this->reception->record_time_from}";
            $es->user_id = $userId;
            $es->patient_id = $this->patient_id;
            $es->discount = $this->discount;
            $es->patient_name = $this->patient->getFullName();
            $es->price_list_id = $is->priceListItem->priceList->id;
            $es->cat_title = $is->priceListItem->priceList->section;
            $es->price_list_item_id = $is->priceListItem->id;
            $es->sub_cat_title = $is->priceListItem->name;
            $es->cat_percent = $dp->percent;
            $es->sub_cat_price = $is->price;
            $es->price_with_discount = $is->price_with_discount;
            $es->paid_sum = $amount;
            $es->sub_cat_amount = $is->amount;
            $es->teeth_amount = $is->teeth_amount;
            $es->employee_earnings = $employeeEarnings;
            if (!$es->save()) {
                return [
                    'status' => 'fail',
                    'message' => print_r($es->getErrors(), true)
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => 'Зарплата сотрудника успешно рассчитана'
        ];
    }

    public function calculateTechnicianSalary($userId): array
    {
        foreach ($this->invoiceServices as $is) {
            if (!$is->priceListItem->technician_price_list_id) {
                continue;
            }

            $technicianSalary = $this->getTechnicianSalary($is);

            if (!$technicianSalary) {
                return $this->getFailResult('Не найден прайс-лист для техника');
            }

            if (!$this->saveTechnicianSalary($userId, $is, $technicianSalary)) {
                return $this->getFailResult('Ошибка при сохранении зарплаты техника');
            }
        }

        return $this->getSuccessResult('Зарплата техника успешно рассчитана');
    }

    private function getTechnicianSalary($is): ?TechnicianPriceList
    {
        return TechnicianPriceList::findOne([
            'id' => $is->priceListItem->technician_price_list_id,
            'status' => TechnicianPriceList::STATUS_ACTIVE
        ]);
    }

    private function saveTechnicianSalary($userId, $is, $technicianSalary): bool
    {
        $ts = new EmployeeSalary();
        $ts->invoice_id = $this->id;
        $ts->invoice_total = $this->getInvoiceTotal();
        $ts->reception_id = $this->reception_id;
        $ts->visit_time = "{$this->reception->record_date} {$this->reception->record_time_from}";
        $ts->user_id = $userId;
        $ts->patient_id = $this->patient_id;
        $ts->discount = $this->discount;
        $ts->patient_name = $this->patient->getFullName();
        $ts->price_list_id = $is->priceListItem->priceList->id;
        $ts->cat_title = $is->priceListItem->priceList->section;
        $ts->price_list_item_id = $is->priceListItem->id;
        $ts->sub_cat_title = $is->priceListItem->name;
        $ts->cat_percent = 0;
        $ts->sub_cat_price = $is->price;
        $ts->price_with_discount = $is->price_with_discount;
        $ts->sub_cat_amount = $is->amount;
        $ts->teeth_amount = $is->teeth_amount;
        $ts->employee_earnings = $is->teeth_amount * $is->amount * $technicianSalary->price;
        return $ts->save();
    }

    public function calculateReport($amount, $technicianId = null): array
    {
        foreach ($this->invoiceServices as $is) {
            $assistantPercent = 0;
            $technicianEarnings = 0;

            $dp = DoctorItemPercent::find()
                ->where(['doctor_id' => $this->doctor_id, 'price_list_item_id' => $is->price_list_item_id])
                ->one();

            if (!isset($dp)) {
                $dp = DoctorPercent::findOne([
                    'user_id' => $this->doctor_id,
                    'price_list_id' => $is->priceListItem->price_list_id
                ]);
                if (!$dp) {
                    return [
                        'status' => 'fail',
                        'message' => 'Не найден процент для сотрудника'
                    ];
                }
            }
            $doctorPercent = $dp->percent;

            if ($this->assistant_id) {
                $ap = DoctorPercent::findOne([
                    'user_id' => $this->assistant_id,
                    'price_list_id' => $is->priceListItem->price_list_id
                ]);
                if (isset($ap)) {
                    $assistantPercent = $ap->percent;
                }
            }

            $invoiceTotal = $this->getInvoiceTotal();

            $price = $is->teeth_amount * $is->price_with_discount * $is->amount;
            $priceListPayPrice = $amount / $invoiceTotal * $price;
            $consumable = ($is->priceListItem->consumable * $is->amount * $is->teeth_amount)
                / $price * $priceListPayPrice;

            $doctorEarnings = ($priceListPayPrice - $consumable) * $doctorPercent / 100;
            $assistantEarnings = !empty($this->assistant_id)
                ? ($priceListPayPrice - $consumable) * $assistantPercent / 100
                : 0;
            $totalEarnings = $doctorEarnings + $assistantEarnings;

            $report = new Report();
            $report->invoice_id = $this->id;
            $report->patient_id = $this->patient_id;
            $report->discount = $this->discount;
            $report->visit_time = "{$this->reception->record_date} {$this->reception->record_time_from}";
            $report->doctor_id = $this->doctor_id;
            $report->doctor_cat_percent = $doctorPercent;
            $report->doctor_earnings = $doctorEarnings;
            $report->assistant_id = $this->assistant_id;
            $report->assistant_cat_percent = $assistantPercent;
            $report->assistant_earnings = $assistantEarnings;
            if ($is->priceListItem->technician_price_list_id) {
                $report->technician_id = $technicianId;
                $report->tech_cat_price = isset($is->priceListItem->technicianPriceList)
                    ? $is->priceListItem->technicianPriceList->price / $price * $amount
                    : 0;
                $report->tech_cat_id = $is->priceListItem->technician_price_list_id;
                $report->technician_earnings
                    = $is->teeth_amount * $is->amount * $report->tech_cat_price;
                $technicianEarnings = $report->technician_earnings;
            }

            $remains = $priceListPayPrice - $totalEarnings - $consumable - $technicianEarnings;

            $report->price_list_id = $is->priceListItem->priceList->id;
            $report->price_list_item_id = $is->priceListItem->id;
            $report->sub_cat_price = $is->price;
            $report->price_with_discount = $is->price_with_discount;
            $report->paid_sum = $priceListPayPrice;
            $report->sub_cat_amount = $is->amount;
            $report->teeth_amount = $is->teeth_amount;
            $report->consumable = $consumable;
            $report->utilities = $is->priceListItem->utilities;
            $report->amortization = $is->priceListItem->amortization;
            $report->marketing = $is->priceListItem->marketing;
            $report->other_expenses = $is->priceListItem->other_expenses;
            $report->remains = $remains;
            $report->profitability = round($remains / $priceListPayPrice * 100, 2);
            $report->created_at = date('Y-m-d H:i:s');
            if (!$report->save()) {
                return [
                    'status' => 'fail',
                    'message' => print_r($report->getErrors(), true)
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => 'Отчет успешно сформирован'
        ];
    }

    private function getFailResult($message): array
    {
        return ['status' => 'fail', 'message' => $message];
    }

    private function getSuccessResult($message): array
    {
        return ['status' => 'success', 'message' => $message];
    }
}
