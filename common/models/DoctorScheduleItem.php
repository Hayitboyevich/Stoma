<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "doctor_schedule_item".
 *
 * @property int $id
 * @property int $doctor_schedule_id
 * @property string $weekday
 * @property string $time_from
 * @property string $time_to
 *
 * @property DoctorSchedule $doctorSchedule
 */
class DoctorScheduleItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'doctor_schedule_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['doctor_schedule_id', 'weekday', 'time_from', 'time_to'], 'required'],
            ['time_to', 'compare', 'compareAttribute' => 'time_from', 'operator' => '>'],
            ['time_from', 'compare', 'compareValue' => '08:00:00', 'operator' => '>='],
            ['time_to', 'compare', 'compareValue' => '20:00:00', 'operator' => '<='],
            [['doctor_schedule_id'], 'integer'],
            [['time_from', 'time_to'], 'safe'],
            [['weekday'], 'string', 'max' => 255],
            [
                ['doctor_schedule_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => DoctorSchedule::class,
                'targetAttribute' => ['doctor_schedule_id' => 'id']
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
            'doctor_schedule_id' => 'Doctor Schedule ID',
            'weekday' => 'Weekday',
            'time_from' => 'Time From',
            'time_to' => 'Time To',
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->time_from = date('H:i:s', strtotime($this->time_from));
            $this->time_to = date('H:i:s', strtotime($this->time_to));
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[DoctorSchedule]].
     *
     * @return ActiveQuery
     */
    public function getDoctorSchedule(): ActiveQuery
    {
        return $this->hasOne(DoctorSchedule::class, ['id' => 'doctor_schedule_id']);
    }

    public static function getScheduleItems($data): array
    {
        $weekday = $data['weekday'];

        $query = self::find()
            ->select([
                'time_from',
                'time_to',
                'in_minutes' => 'ROUND(TIME_TO_SEC(TIMEDIFF(time_to,time_from))/60)',
                'start_position' => 'ROUND(TIME_TO_SEC(TIMEDIFF(time_from,"08:00:00"))/60)'
            ])
            ->where([
                'doctor_schedule_id' => $data['schedule_id'],
                'weekday' => $weekday
            ]);

        return $query->asArray()->all();
    }
}
