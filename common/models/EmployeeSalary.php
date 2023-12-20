<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "employee_salary".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $invoice_total
 * @property int|null $reception_id
 * @property string|null $visit_time
 * @property int|null $user_id
 * @property int|null $patient_id
 * @property int $discount Скидка пациент
 * @property string|null $patient_name
 * @property int|null $price_list_id
 * @property string|null $cat_title
 * @property int|null $price_list_item_id
 * @property string|null $sub_cat_title
 * @property int|null $cat_percent
 * @property int|null $sub_cat_price
 * @property int $price_with_discount Цена подкатегории со скидкой
 * @property float paid_sum Сумма оплаты
 * @property int|null $sub_cat_amount
 * @property int|null $teeth_amount
 * @property float|null $employee_earnings
 * @property string|null $created_at
 *
 * @property Invoice $invoice
 * @property Patient $patient
 * @property PriceList $priceList
 * @property PriceListItem $priceListItem
 * @property Reception $reception
 * @property User $user
 */
class EmployeeSalary extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'employee_salary';
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
                    'invoice_total',
                    'reception_id',
                    'user_id',
                    'patient_id',
                    'price_list_id',
                    'price_list_item_id'
                ],
                'integer'
            ],
            [
                [
                    'cat_percent',
                    'sub_cat_price',
                    'sub_cat_amount',
                    'employee_earnings',
                    'teeth_amount',
                    'discount',
                    'price_with_discount',
                    'paid_sum'
                ],
                'number'
            ],
            [['visit_time', 'created_at'], 'safe'],
            [['cat_title', 'sub_cat_title', 'patient_name'], 'string', 'max' => 255],
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
            [
                ['reception_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Reception::class,
                'targetAttribute' => ['reception_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
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
            'invoice_total' => 'Invoice Total',
            'reception_id' => 'Reception ID',
            'visit_time' => 'Visit Time',
            'user_id' => 'User ID',
            'patient_id' => 'Patient ID',
            'patient_name' => 'Имя пациента',
            'price_list_id' => 'Price List ID',
            'cat_title' => 'Cat Title',
            'price_list_item_id' => 'Price List Item ID',
            'sub_cat_title' => 'Sub Cat Title',
            'cat_percent' => 'Cat Percent',
            'sub_cat_price' => 'Sub Cat Price',
            'sub_cat_amount' => 'Sub Cat Amount',
            'employee_earnings' => 'Employee Earnings',
            'created_at' => 'Created At',
        ];
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
     * Gets query for [[Reception]].
     *
     * @return ActiveQuery
     */
    public function getReception(): ActiveQuery
    {
        return $this->hasOne(Reception::class, ['id' => 'reception_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
