<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $recipient_patient_id
 * @property string $payment_method
 * @property int $amount
 * @property int $is_foreign_currency
 * @property int $foreign_currency_rate
 * @property int $foreign_currency_amount
 * @property int $user_id
 * @property int $is_transfer
 * @property int|null $invoice_id
 * @property string|null $invoice_number
 * @property string $type
 * @property string $created_at
 * @property string $cancel_reason
 *
 * @property Invoice $invoice
 * @property Patient $patient
 * @property User $user
 */
class Transaction extends ActiveRecord
{
    public const TYPE_PAY = 'pay';
    public const TYPE_ADD_MONEY = 'add_money';
    public const TYPE_WITHDRAW_MONEY = 'withdraw_money';
    public const TYPE_TRANSFER = 'transfer';

    public const IS_TRANSFER = 1;
    public const IS_NOT_TRANSFER = 0;

    public const IS_FOREIGN_CURRENCY = 1;
    public const IS_NOT_FOREIGN_CURRENCY = 0;

    public const NOT_PAYMENT_METHOD = '-';

    public const PAYMENT_METHOD_TRANSFER = 'transfer';

    public const TYPE = [
        self::TYPE_PAY => 'Оплата',
        self::TYPE_ADD_MONEY => 'Взнос',
        self::TYPE_TRANSFER => 'Перевод',
        self::TYPE_WITHDRAW_MONEY => 'Снять деньги'
    ];
    public const PAYMENT_METHODS = [
        'cash' => 'Налич.',
        'uzcard' => 'Uzcard',
        'humo' => 'Humo',
        'click' => 'Click',
        'payme' => 'Payme',
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'transfer' => 'Переч.',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['patient_id', 'payment_method', 'amount', 'user_id', 'type'], 'required'],
            [
                ['patient_id', 'user_id', 'invoice_id', 'recipient_patient_id', 'is_transfer', 'is_foreign_currency'],
                'integer'
            ],
            [
                ['amount', 'foreign_currency_rate', 'foreign_currency_amount'],
                'number',
                'numberPattern' => '/^\d{1,8}(\.\d{1,2})?$/'
            ],
            [['created_at'], 'safe'],
            [['is_transfer'], 'default', 'value' => self::IS_NOT_TRANSFER],
            [['payment_method', 'invoice_number', 'type', 'cancel_reason'], 'string', 'max' => 255],
            [
                ['invoice_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Invoice::class,
                'targetAttribute' => ['invoice_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::class,
                'targetAttribute' => ['patient_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
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
            'patient_id' => 'Patient ID',
            'payment_method' => 'Payment Method',
            'amount' => 'Amount',
            'user_id' => 'User ID',
            'invoice_id' => 'Invoice ID',
            'invoice_number' => 'Invoice Number',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return ActiveQuery
     */
    public function getInvoice(): ActiveQuery
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoice_id']);
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
     * Gets query for [[Patient]].
     *
     * @return ActiveQuery
     */
    public function getReceptionPatient(): ActiveQuery
    {
        return $this->hasOne(Patient::class, ['id' => 'recipient_patient_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param $data
     * @return array
     */
    public static function getCashierStatistics($data): array
    {
        $userId = Yii::$app->user->identity->id;

        $query = self::find();

        foreach (self::PAYMENT_METHODS as $method => $name) {
            $query->addSelect([
                "COALESCE(
                    SUM(CASE WHEN payment_method = '$method' THEN amount ELSE 0 END),
                0) AS $method"
            ]);
        }

        $query->addSelect([
            "COALESCE(
                SUM(CASE WHEN is_foreign_currency = " . Transaction::IS_FOREIGN_CURRENCY . " THEN foreign_currency_amount ELSE 0 END),
            0) AS foreign_currency"
        ]);

        $query->where(['type' => self::TYPE_ADD_MONEY])
            ->andWhere(['is_transfer' => self::IS_NOT_TRANSFER])
            ->andWhere([
                'and',
                ['>=', 'created_at', $data['start_date'] . ' 00:00:00'],
                ['<=', 'created_at', $data['end_date'] . ' 23:59:59']
            ]);

        if (Yii::$app->user->can('cashier')) {
            $query->andWhere(['user_id' => $userId]);
        }

        return $query->asArray()->one();
    }
}
