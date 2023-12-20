<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "patient_examination".
 *
 * @property int $id
 * @property int|null $visit_id
 * @property int $patient_id
 * @property int $doctor_id
 * @property string|null $complaints
 * @property string|null $anamnesis
 * @property string|null $weight
 * @property string|null $height
 * @property string|null $head_circumference
 * @property string|null $temperature
 * @property string|null $inspection_data
 * @property string|null $diagnosis
 * @property string|null $recommendations
 * @property string|null $datetime
 *
 * @property User $doctor
 * @property Patient $patient
 * @property Reception $visit
 */
class PatientExamination extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'patient_examination';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['visit_id', 'patient_id', 'doctor_id'], 'integer'],
            [['patient_id', 'doctor_id'], 'required'],
            [['complaints', 'anamnesis', 'inspection_data', 'diagnosis', 'recommendations'], 'string'],
            [['datetime'], 'safe'],
            [['weight', 'height', 'head_circumference', 'temperature'], 'string', 'max' => 255],
            [
                ['doctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['doctor_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::class,
                'targetAttribute' => ['patient_id' => 'id']
            ],
            [
                ['visit_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Reception::class,
                'targetAttribute' => ['visit_id' => 'id']
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
            'visit_id' => 'Visit ID',
            'patient_id' => 'Patient ID',
            'doctor_id' => 'Doctor ID',
            'complaints' => 'Жалобы',
            'anamnesis' => 'Анамнез',
            'weight' => 'Вес',
            'height' => 'Рост',
            'head_circumference' => 'Окружность головы',
            'temperature' => 'Температура',
            'inspection_data' => 'Данные осмотра',
            'diagnosis' => 'Диагноз',
            'recommendations' => 'Рекомендации',
            'datetime' => 'Дата и время'
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
     * Gets query for [[Patient]].
     *
     * @return ActiveQuery
     */
    public function getPatient(): ActiveQuery
    {
        return $this->hasOne(Patient::class, ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[Visit]].
     *
     * @return ActiveQuery
     */
    public function getVisit(): ActiveQuery
    {
        return $this->hasOne(Reception::class, ['id' => 'visit_id']);
    }
}
