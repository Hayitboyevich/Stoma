<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "discount_request".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $patient_id
 * @property int|null $discount
 * @property string|null $status
 * @property int|null $approved_user_id
 * @property string|null $approved_time
 * @property string|null $created_at
 *
 * @property User $approvedUser
 * @property Patient $patient
 * @property User $user
 */
class DiscountRequest extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'discount_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'patient_id', 'discount', 'approved_user_id'], 'integer'],
            [['approved_time', 'created_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [
                ['approved_user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['approved_user_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::class,
                'targetAttribute' => ['patient_id' => 'id']
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
            'user_id' => 'ID Врача',
            'patient_id' => 'ID Пациента',
            'discount' => 'Размер скидки',
            'status' => 'Статус заявки',
            'approved_user_id' => 'ID пользователя который утвердил скидку',
            'approved_time' => 'Время утврждения',
            'created_at' => 'Дата создания заявки',
        ];
    }

    /**
     * Gets query for [[ApprovedUser]].
     *
     * @return ActiveQuery
     */
    public function getApprovedUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'approved_user_id']);
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
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
