<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "doctor_patient".
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $patient_id
 *
 * @property User $doctor
 * @property Patient $patient
 */
class DoctorPatient extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'doctor_patient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['doctor_id', 'patient_id'], 'required'],
            [['doctor_id', 'patient_id'], 'integer'],
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
            'patient_id' => 'Patient ID',
        ];
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return ActiveQuery
     */
    public function getDoctor(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return ActiveQuery
     */
    public function getPatient(): ActiveQuery
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }
}
