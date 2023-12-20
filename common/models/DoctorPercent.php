<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "doctor_percent".
 *
 * @property int $id
 * @property int $user_id
 * @property int $price_list_id
 * @property float $percent
 *
 * @property User $doctor
 * @property PriceList $priceList
 */
class DoctorPercent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'doctor_percent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'price_list_id', 'percent'], 'required'],
            [['user_id', 'price_list_id'], 'integer'],
            [['percent'], 'safe'],
            ['percent', 'number', 'min' => 0, 'max' => 100],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                ['price_list_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PriceList::class,
                'targetAttribute' => ['price_list_id' => 'id']
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
            'user_id' => 'Doctor ID',
            'price_list_id' => 'Price List ID',
            'percent' => 'Процент',
        ];
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return ActiveQuery
     */
    public function getDoctor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
}
