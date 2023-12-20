<?php

namespace common\models;

use common\models\traits\Patient\HasRelationships;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "patient".
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $phone
 * @property string|null $note
 * @property int|null $chat_id
 * @property string|null $chat_status
 * @property string|null $dob
 * @property boolean $vip
 * @property string $last_visited
 * @property string $gender
 * @property int|null $media_id
 * @property string $created_at
 * @property string $note_update_at
 * @property int|null $discount
 * @property int|null $doctor_id
 * @property int $deleted
 * @property int $last_activity
 * @property int|null $old_id
 */
class Patient extends ActiveRecord
{
    use HasRelationships;

    public const DELETED = 1;
    public const NOT_DELETED = 0;

    public const GENDER = [
        'M' => 'Муж.',
        'F' => 'Жен.',
        '' => '',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'patient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['firstname', 'lastname', 'chat_status', 'gender'], 'string', 'max' => 255],
            [
                ['firstname', 'lastname'],
                'match',
                'pattern' => '/^[А-Яа-яA-Za-z\s]+$/u'
            ],
            [
                'phone',
                'match',
                'pattern' => '/^[0-9\+]+$/u'
            ],
            [['chat_id', 'media_id', 'discount', 'doctor_id', 'deleted', 'old_id'], 'integer'],
            [['dob', 'last_visited', 'created_at', 'note', 'phone'], 'string'],
            ['discount', 'default', 'value' => 0],
            [['vip'], 'boolean'],
            [['phone'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'phone' => 'Телефон',
            'chat_id' => 'Уникальный номер телеграм',
            'chat_status' => 'Статус чата',
            'dob' => 'День рождения',
            'vip' => 'VIP',
            'last_visited' => 'Последний визит',
            'gender' => 'Пол',
            'media_id' => 'Фото',
            'created_at' => 'Дата регистрации',
            'discount' => 'Скидка',
            'doctor_id' => 'Лечащий врач',
            'deleted' => 'Удален',
            'last_activity' => 'Последняя активность',
            'old_id' => 'ID из старой базы',
            'note' => 'Примечание'
        ];
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->lastname . " " . $this->firstname;
    }

    /**
     * @param $message
     * @return Patient
     */
    public static function newSubscriberFromMessage($message): Patient
    {
        $subscriber = new Patient();
        $subscriber->chat_id = $message->from->id;

        $subscriber->save();
        return $subscriber;
    }

    /**
     * @return ActiveQuery
     */
    public function getNewVisits(): ActiveQuery
    {
        return $this->hasMany(Reception::className(), ['patient_id' => 'id'])
            ->andWhere([
                'and',
                ['canceled' => self::NOT_DELETED],
                ['>=', 'record_date', date('Y-m-d')]
            ])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getOldVisits(): ActiveQuery
    {
        return $this->hasMany(Reception::className(), ['patient_id' => 'id'])
            ->andWhere([
                'and',
                ['canceled' => self::NOT_DELETED],
                ['<', 'record_date', date('Y-m-d')]
            ])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @param $data
     * @return array
     */
    public function getFormattedInvoices($data): array
    {
        return InvoiceServices::find()
            ->select([
                'invoice_id',
                'invoice.patient_id',
                'invoice.invoice_number',
                'invoice.created_at',
                'invoice.closed_at',
                'invoice.id',
                'invoice.status',
                'invoice.type',
                'GROUP_CONCAT(DISTINCT(teeth)) AS all_teeth',
                'SUM(price_with_discount * amount * teeth_amount) AS total_price'
            ])
            ->joinWith('invoice')
            ->where([
                'invoice.patient_id' => $this->id,
                'invoice.preliminary' => $data['preliminary']
            ])
            ->groupBy('invoice_id')
            ->asArray()
            ->all();
    }

    /**
     * @return int[]
     */
    public function getTransactionTotals(): array
    {
        $currentTotals = [
            Transaction::TYPE_ADD_MONEY => 0,
            Transaction::TYPE_PAY => 0,
            Transaction::TYPE_WITHDRAW_MONEY => 0
        ];

        $totals = Transaction::find()
            ->select(['type', 'SUM(amount) AS total'])
            ->where(['patient_id' => $this->id])
            ->groupBy(['type'])
            ->asArray()
            ->all();

        foreach ($totals as $total) {
            $currentTotals[$total['type']] += $total['total'];
        }

        return $currentTotals;
    }

    /**
     * @return int
     */
    public function getPrepayment(): int
    {
        $patientBalance = $this->getTransactionTotals();
        $diff = $patientBalance['add_money'] - $patientBalance['pay'] - $patientBalance['withdraw_money'];
        if ($diff >= 0) {
            return $diff;
        }

        return 0;
    }

    public function checkDebt(): bool
    {
        return Invoice::find()
            ->where(['patient_id' => $this->id, 'preliminary' => Invoice::NOT_PRELIMINARY])
            ->andWhere([
                'OR',
                ['type' => Invoice::TYPE_NEW],
                ['type' => Invoice::TYPE_DEBT, 'status' => Invoice::STATUS_UNPAID],
                ['type' => Invoice::TYPE_INSURANCE, 'status' => Invoice::STATUS_UNPAID]
            ])
            ->exists();
    }

    /**
     * @return bool
     */
    public function checkAppointmentAlreadyExist(): bool
    {
        return AppointmentRequest::find()
                ->where(['patient_id' => $this->id, 'status' => AppointmentRequest::STATUS_NEW])
                ->count() > 0;
    }

    public function getTotalPaidInvoices()
    {
        $total = Transaction::find()
            ->where([
                'type' => Transaction::TYPE_PAY,
                'patient_id' => $this->id,
                'is_transfer' => Transaction::IS_NOT_TRANSFER
            ])
            ->sum('amount');

        return is_null($total) ? 0 : $total;
    }

    /**
     * @return bool|int|mixed|string
     */
    public function getDebt()
    {
        $invoiceDebt = Invoice::find()
            ->alias('i')
            ->select(["COALESCE(sum(ins.amount * ins.price_with_discount * ins.teeth_amount), 0) AS total"])
            ->innerJoin('invoice_services as ins', 'i.id = ins.invoice_id')
            ->where([
                'i.patient_id' => $this->id,
                'i.preliminary' => Invoice::NOT_PRELIMINARY
            ])
            ->andWhere([
                'or',
                [
                    'i.type' => Invoice::TYPE_NEW
                ],
                [
                    'i.type' => Invoice::TYPE_INSURANCE,
                    'i.status' => Invoice::STATUS_UNPAID,
                ]
            ])
            ->asArray()
            ->one();


        $debt = 0;
        $invoices = Invoice::find()
            ->where([
                'patient_id' => $this->id,
                'preliminary' => Invoice::NOT_PRELIMINARY
            ])
            ->andWhere([
                'type' => Invoice::TYPE_DEBT,
                'status' => Invoice::STATUS_UNPAID,
            ])
            ->all();

        foreach ($invoices as $invoice) {
            $debt += $invoice->getRemains();
        }

        return $invoiceDebt['total'] + $debt;
    }

    public function autoAssigneeDiscount()
    {
        $autoDiscountMin = Config::findOne(['key' => 'auto_discount_total']);
        if (!$autoDiscountMin) {
            return false;
        }

        if ($this->discount >= 5) {
            return false;
        }

        if (!$this->checkDebt() && $this->getTotalPaidInvoices() >= (int)$autoDiscountMin->value) {
            $this->discount = 5;
            $this->save(false);
        }
    }
}
