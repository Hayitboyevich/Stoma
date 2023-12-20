<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_services".
 *
 * @property int $id
 * @property int $invoice_id Счет
 * @property int $price_list_item_id Виды работ
 * @property int|null $amount Количество
 * @property int $price Цена
 * @property int $price_with_discount Цена со скидкой
 * @property string $teeth Зубы
 * @property string $title Зубы
 * @property int $teeth_amount
 *
 * @property Invoice $invoice
 * @property PriceListItem $priceListItem
 */
class InvoiceServices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'price_list_item_id', 'price'], 'required'],
            [['invoice_id', 'price_list_item_id', 'amount', 'price', 'teeth_amount', 'price_with_discount'], 'integer'],
            [['teeth', 'title'], 'string', 'max' => 255],
            [
                ['invoice_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Invoice::className(),
                'targetAttribute' => ['invoice_id' => 'id']
            ],
            [
                ['price_list_item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PriceListItem::className(),
                'targetAttribute' => ['price_list_item_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'price_list_item_id' => 'Price List Item ID',
            'amount' => 'Amount',
            'price' => 'Price',
            'teeth' => 'Teeth',
            'title' => 'Название услуги',
            'teeth_amount' => 'Количество зубов',
        ];
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[PriceListItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPriceListItem()
    {
        return $this->hasOne(PriceListItem::className(), ['id' => 'price_list_item_id']);
    }
}
