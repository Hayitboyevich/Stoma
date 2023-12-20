<?php

namespace common\models\constants;

use Yii;

class UserRole
{
    const ROLE_RECEPTION = 'reception';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_DIRECTOR = 'director';
    const ROLE_ADMIN = 'admin';
    const ROLE_ASSISTANT = 'assistant';
    const ROLE_CASHIER = 'cashier';
    const ROLE_ACCOUNTANT = 'accountant';
    const ROLE_TECHNICIAN = 'technician';
    const ROLE_BUYER = 'buyer';
    const ROLE_API = 'api';

    /**
     * @return array
     */
    public static function getArray(): array
    {
        return [
            self::ROLE_RECEPTION => self::getString(self::ROLE_RECEPTION),
            self::ROLE_DOCTOR => self::getString(self::ROLE_DOCTOR),
            self::ROLE_DIRECTOR => self::getString(self::ROLE_DIRECTOR),
            self::ROLE_ADMIN => self::getString(self::ROLE_ADMIN),
            self::ROLE_ASSISTANT => self::getString(self::ROLE_ASSISTANT),
            self::ROLE_CASHIER => self::getString(self::ROLE_CASHIER),
            self::ROLE_ACCOUNTANT => self::getString(self::ROLE_ACCOUNTANT),
            self::ROLE_TECHNICIAN => self::getString(self::ROLE_TECHNICIAN),
            self::ROLE_BUYER => self::getString(self::ROLE_BUYER),
            self::ROLE_API => self::getString(self::ROLE_API)
        ];
    }

    /**
     * @param $user_role
     *
     * @return string
     */
    public static function getString($user_role): string
    {
        switch ($user_role) {
            case self::ROLE_RECEPTION:
                return Yii::t('app', 'Приемная');
            case self::ROLE_DOCTOR:
                return Yii::t('app', 'Врач');
            case self::ROLE_DIRECTOR:
                return Yii::t('app', 'Директор');
            case self::ROLE_ADMIN:
                return Yii::t('app', 'Администратор');
            case self::ROLE_ASSISTANT:
                return Yii::t('app', 'Ассистент');
            case self::ROLE_CASHIER:
                return Yii::t('app', 'Кассир');
            case self::ROLE_ACCOUNTANT:
                return Yii::t('app', 'Бухгалтер');
            case self::ROLE_TECHNICIAN:
                return Yii::t('app', 'Техник');
            case self::ROLE_BUYER:
                return Yii::t('app', 'Закупщик');
            case self::ROLE_API:
                return Yii::t('app', 'Апи');
            default:
                return Yii::t('app', "Неизвестный");
        }
    }
}
