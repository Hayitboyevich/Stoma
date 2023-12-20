<?php

namespace common\models;

use yii\base\Model;

class AppointmentRequestForm extends Model
{
    public $first_name;
    public $last_name;
    public $phone;
    public $parent_first_name;
    public $parent_last_name;
    public $dob;

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'phone', 'parent_first_name', 'parent_last_name', 'dob'], 'required'],
            [['first_name', 'last_name', 'parent_first_name', 'parent_last_name'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 13],
            [['dob'], 'string', 'max' => 10],
            [['first_name', 'last_name', 'parent_first_name', 'parent_last_name'], 'trim'],
            [
                ['first_name', 'last_name', 'parent_first_name', 'parent_last_name'],
                'match',
                'pattern' => '/^[a-zA-Zа-яА-Я]+$/'
            ],
            [['phone'], 'match', 'pattern' => '/^\+?[0-9]{10,12}$/'],
            [['dob'], 'date', 'format' => 'php:Y-m-d'],
            [
                ['dob'],
                'match',
                'pattern' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
                'message' => 'Date of birth must be in format YYYY-MM-DD'
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'parent_first_name' => 'Parent First Name',
            'parent_last_name' => 'Parent Last Name',
            'dob' => 'Date of Birth'
        ];
    }
}
