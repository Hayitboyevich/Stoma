<?php

namespace common\models;

use yii\base\Model;

class WithdrawMoney extends Model
{
    public $patient_id;
    public $amount;
    public $reason;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['patient_id', 'reason', 'amount'], 'required'],
            [['amount'], 'number', 'min' => 1],
            [['reason'], 'string', 'max' => 255],
            ['amount', 'validateAmount']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'patient_id' => 'Пациент',
            'reason' => 'Причина',
            'amount' => 'Сумма',
        ];
    }

    public function validateAmount($attribute): void
    {
        $checkPatientBalance = Patient::findOne($this->patient_id);
        if (!$checkPatientBalance || $checkPatientBalance->getPrepayment() < $this->amount) {
            $this->addError($attribute, 'Недостаточно средств');
        }
    }
}
