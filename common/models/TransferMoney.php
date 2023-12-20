<?php

namespace common\models;

use yii\base\Model;

class TransferMoney extends Model
{
    public $sender_patient_id;
    public $recipient_patient_id;
    public $amount;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['sender_patient_id', 'recipient_patient_id', 'amount'], 'required'],
            [
                'sender_patient_id',
                'compare',
                'compareAttribute' => 'recipient_patient_id',
                'operator' => '!=',
            ],
            [['amount'], 'number', 'min' => 1],
            ['amount', 'validateAmount']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'sender_patient_id' => 'Отправитель пациент',
            'recipient_patient_id' => 'Получатель пациент',
            'amount' => 'Сумма',
        ];
    }

    public function validateAmount($attribute): void
    {
        $checkPatientBalance = Patient::findOne($this->sender_patient_id);
        if (!$checkPatientBalance || $checkPatientBalance->getPrepayment() < $this->amount) {
            $this->addError($attribute, 'Недостаточно средств');
        }
    }
}
