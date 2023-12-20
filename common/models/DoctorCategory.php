<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "doctor_category".
 *
 * @property int $id
 * @property int|null $doctor_id
 * @property int|null $category_id
 *
 * @property PriceList $category
 * @property User $doctor
 */
class DoctorCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'doctor_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['doctor_id', 'category_id'], 'integer'],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PriceList::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['doctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['doctor_id' => 'id']
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
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(PriceList::class, ['id' => 'category_id']);
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
}
