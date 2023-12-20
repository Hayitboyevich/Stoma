<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "old_employee".
 *
 * @property int $id ID СОТРУДНИКА
 * @property string|null $last_name ФАМИЛИЯ
 * @property string|null $first_name ИМЯ
 * @property string|null $patronymic ОТЧЕСТВО
 * @property string|null $dob ДАТА РОЖДЕНИЯ
 * @property string|null $status СТАТУС
 * @property string|null $work_start_date ДАТА НАЧАЛА РАБОТЫ
 * @property string|null $work_end_date ДАТА ОКОНЧАНИЯ РАБОТЫ
 * @property string|null $email EMAIL
 * @property string|null $initials ФИО СОКРАЩЕННО
 * @property string|null $phone МОБ. ТЕЛЕФОН
 * @property string|null $phone_home ДОМ. ТЕЛЕФОН
 * @property string|null $phone_work РАБ. ТЕЛЕФОН
 */
class OldEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'old_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dob', 'work_start_date', 'work_end_date'], 'safe'],
            [
                [
                    'last_name',
                    'first_name',
                    'patronymic',
                    'status',
                    'email',
                    'initials',
                    'phone',
                    'phone_home',
                    'phone_work'
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
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'patronymic' => 'Patronymic',
            'dob' => 'Dob',
            'status' => 'Status',
            'work_start_date' => 'Work Start Date',
            'work_end_date' => 'Work End Date',
            'email' => 'Email',
            'initials' => 'Initials',
            'phone' => 'Phone',
            'phone_home' => 'Phone Home',
            'phone_work' => 'Phone Work',
        ];
    }
}
