<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "old_patient".
 *
 * @property int $id ID ПАЦИЕНТА
 * @property int|null $card_number НОМЕР КАРТЫ
 * @property string|null $first_visit ПЕРВЫЙ ВИЗИТ
 * @property string|null $last_name ФАМИЛИЯ
 * @property string|null $first_name ИМЯ
 * @property string|null $patronymic ОТЧЕСТВО
 * @property string|null $gender ПОЛ
 * @property string|null $dob ДАТА РОЖДЕНИЯ
 * @property string|null $phone МОБ. ТЕЛЕФОН
 * @property string|null $phone_home ДОМ. ТЕЛЕФОН
 * @property string|null $phone_work РАБ. ТЕЛЕФОН
 * @property int|null $discount СКИДКА, %
 * @property string|null $home_address ДОМ. АДРЕС
 * @property int|null $doctor_id ID ЛЕЧАЩЕГО ВРАЧА
 * @property int|null $hygienist_id ID ГИГИЕНИСТА
 * @property string|null $source ИСТОЧНИК
 * @property string|null $recommended_patient КТО РЕКОМЕНДОВАЛ (НОМЕР КАРТЫ ПАЦИЕНТА)
 * @property string|null $recommended_user КТО РЕКОМЕНДОВАЛ (СОТРУДНИК)
 * @property string|null $who_were_recommended КОГО РЕКОМЕНДОВАЛИ
 * @property string|null $patient_status СТАТУС ПАЦИЕНТА
 * @property int|null $debt ДОЛГ
 * @property int|null $credit АВАНС
 * @property int|null $recommendations_amount КОЛ-ВО РЕКОМЕНДАЦИЙ
 * @property string|null $email EMAIL
 * @property string|null $note ПРИМЕЧАНИЕ
 */
class OldPatient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'old_patient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['card_number', 'discount', 'doctor_id', 'hygienist_id', 'debt', 'credit', 'recommendations_amount'],
                'integer'
            ],
            [['first_visit', 'dob'], 'safe'],
            [
                [
                    'last_name',
                    'first_name',
                    'patronymic',
                    'gender',
                    'phone',
                    'phone_home',
                    'phone_work',
                    'home_address',
                    'source',
                    'recommended_patient',
                    'recommended_user',
                    'who_were_recommended',
                    'patient_status'
                ],
                'string',
                'max' => 255
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
            'card_number' => 'Card Number',
            'first_visit' => 'First Visit',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'patronymic' => 'Patronymic',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'phone' => 'Phone',
            'phone_home' => 'Phone Home',
            'phone_work' => 'Phone Work',
            'discount' => 'Discount',
            'home_address' => 'Home Address',
            'doctor_id' => 'Doctor ID',
            'hygienist_id' => 'Hygienist ID',
            'source' => 'Source',
            'recommended_patient' => 'Recommended Patient',
            'recommended_user' => 'Recommended User',
            'who_were_recommended' => 'Who Were Recommended',
            'patient_status' => 'Patient Status',
            'debt' => 'Debt',
            'credit' => 'Credit',
            'recommendations_amount' => 'Recommendations Amount',
        ];
    }
}
