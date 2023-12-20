<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "doctor_schedule".
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $date_from
 * @property string|null $date_to
 * @property int|null $current
 *
 * @property User $doctor
 */
class DoctorSchedule extends ActiveRecord
{
    public const ACTIVE_SCHEDULE = 1;
    public const INACTIVE_SCHEDULE = 0;
    public const WEEKDAYS = [
        'mon' => 'ПН',
        'tue' => 'ВТ',
        'wed' => 'СР',
        'thu' => 'ЧТ',
        'fri' => 'ПТ',
        'sat' => 'СБ',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'doctor_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['doctor_id', 'date_from'], 'required'],
            [['doctor_id', 'current'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
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
            'doctor_id' => 'ID Врача',
            'date_from' => 'С',
            'date_to' => 'По',
            'current' => 'Актуальный',
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
     * Gets query for [[DoctorScheduleItem]].
     *
     * @return ActiveQuery
     */
    public function getDoctorScheduleItem(): ActiveQuery
    {
        return $this->hasOne(DoctorScheduleItem::class, ['doctor_schedule_id' => 'id']);
    }
}
