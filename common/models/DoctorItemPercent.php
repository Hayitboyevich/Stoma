<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "doctor_item_percent".
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $price_list_item_id
 * @property int $percent
 *
 * @property User $doctor
 * @property PriceListItem $priceListItem
 */
class DoctorItemPercent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'doctor_item_percent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'price_list_item_id', 'percent'], 'required'],
            [['doctor_id', 'price_list_item_id', 'percent'], 'integer'],
            [
                ['doctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['doctor_id' => 'id']
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
            'doctor_id' => 'Doctor ID',
            'price_list_item_id' => 'Price List Item ID',
            'percent' => 'Percent',
        ];
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
     * Gets query for [[PriceListItem]].
     *
     * @return ActiveQuery
     */
    public function getPriceListItem(): ActiveQuery
    {
        return $this->hasOne(PriceListItem::class, ['id' => 'price_list_item_id']);
    }
}
