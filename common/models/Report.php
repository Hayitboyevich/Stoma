<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "report".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $patient_id
 * @property string|null $visit_time
 * @property int|null $doctor_id
 * @property float|null $doctor_cat_percent
 * @property float|null $doctor_earnings
 * @property int|null $assistant_id
 * @property float|null $assistant_cat_percent
 * @property float|null $assistant_earnings
 * @property int|null $price_list_id
 * @property int|null $price_list_item_id
 * @property int|null $sub_cat_price
 * @property int|null $sub_cat_amount
 * @property int|null $teeth_amount
 * @property float $consumable Расходный материал
 * @property float $utilities Коммунальные услуги
 * @property float $amortization Амортизация оборудования и обслуживание
 * @property float $marketing Маркетинг
 * @property float $other_expenses Прочие расходы
 * @property float $remains Остаток
 * @property float $profitability Рентабельность
 * @property string $created_at
 * @property int $discount Скидка пациент
 * @property int $price_with_discount Цена подкатегории со скидкой
 * @property float $paid_sum Сумма оплаты
 * @property int|null $technician_id ID техника
 * @property int|null $tech_cat_price Цена категории техника
 * @property int|null $tech_cat_id ID категории техника
 * @property float|null $technician_earnings Зарплата техника
 *
 * @property User $assistant
 * @property User $doctor
 * @property Invoice $invoice
 * @property Patient $patient
 * @property PriceList $priceList
 * @property PriceListItem $priceListItem
 */
class Report extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'invoice_id',
                    'patient_id',
                    'doctor_id',
                    'assistant_id',
                    'price_list_id',
                    'price_list_item_id',
                    'sub_cat_amount',
                    'teeth_amount',
                    'discount',
                    'technician_id',
                    'tech_cat_id'
                ],
                'integer'
            ],
            [['visit_time', 'created_at'], 'safe'],
            [
                [
                    'doctor_cat_percent',
                    'assistant_cat_percent',
                    'consumable',
                    'utilities',
                    'amortization',
                    'marketing',
                    'other_expenses',
                    'profitability',
                    'remains',
                    'price_with_discount',
                    'assistant_earnings',
                    'doctor_earnings',
                    'sub_cat_price',
                    'tech_cat_price',
                    'technician_earnings',
                    'paid_sum'
                ],
                'number'
            ],
            [
                ['assistant_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['assistant_id' => 'id']
            ],
            [
                ['doctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['doctor_id' => 'id']
            ],
            [
                ['invoice_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Invoice::class,
                'targetAttribute' => ['invoice_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::class,
                'targetAttribute' => ['patient_id' => 'id']
            ],
            [
                ['price_list_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PriceList::class,
                'targetAttribute' => ['price_list_id' => 'id']
            ],
            [
                ['price_list_item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PriceListItem::class,
                'targetAttribute' => ['price_list_item_id' => 'id']
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
            'invoice_id' => 'Invoice ID',
            'patient_id' => 'Patient ID',
            'visit_time' => 'Visit Time',
            'doctor_id' => 'Doctor ID',
            'doctor_cat_percent' => 'Doctor Cat Percent',
            'doctor_earnings' => 'Doctor Earnings',
            'assistant_id' => 'Assistant ID',
            'assistant_cat_percent' => 'Assistant Cat Percent',
            'assistant_earnings' => 'Assistant Earnings',
            'price_list_id' => 'Price List ID',
            'price_list_item_id' => 'Price List Item ID',
            'sub_cat_price' => 'Sub Cat Price',
            'sub_cat_amount' => 'Sub Cat Amount',
            'teeth_amount' => 'Teeth Amount',
            'consumable' => 'Consumable',
            'utilities' => 'Utilities',
            'amortization' => 'Amortization',
            'marketing' => 'Marketing',
            'other_expenses' => 'Other Expenses',
            'remains' => 'Remains',
            'profitability' => 'Profitability',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Assistant]].
     *
     * @return ActiveQuery
     */
    public function getAssistant(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'assistant_id']);
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return ActiveQuery
     */
    public function getDoctor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'doctor_id']);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return ActiveQuery
     */
    public function getInvoice(): ActiveQuery
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return ActiveQuery
     */
    public function getPatient(): ActiveQuery
    {
        return $this->hasOne(Patient::class, ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[PriceList]].
     *
     * @return ActiveQuery
     */
    public function getPriceList(): ActiveQuery
    {
        return $this->hasOne(PriceList::class, ['id' => 'price_list_id']);
    }

    /**
     * Gets query for [[PriceListItem]].
     *
     * @return ActiveQuery
     */
    public function getPriceListItem(): ActiveQuery
    {
        return $this->hasOne(PriceListItem::class, ['id' => 'price_list_item_id']);
    }

    /**
     * Gets query for [[TechnicianPriceList]].
     *
     * @return ActiveQuery
     */
    public function getTechnicianPriceList(): ActiveQuery
    {
        return $this->hasOne(TechnicianPriceList::class, ['id' => 'tech_cat_id']);
    }
}
