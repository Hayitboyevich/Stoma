<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "sms_notification".
 *
 * @property int $id
 * @property string $message
 * @property int|null $user_id
 * @property int|null $patient_id
 * @property string|null $response
 * @property string|null $request_data
 * @property string|null $status
 * @property string $phone
 * @property string|null $created_at
 * @property string|null $object_type
 * @property integer|null $object_id
 *
 * @property Patient $patient
 * @property User $user
 */
class SmsNotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'sms_notification';
    }

    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';

    public const STATUS = [
        self::STATUS_SUCCESS => 'Успешно',
        self::STATUS_FAIL => 'Сбой'
    ];

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['message', 'phone'], 'required'],
            [['message', 'response', 'object_type', 'request_data'], 'string'],
            [['user_id', 'patient_id', 'object_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status', 'phone'], 'string', 'max' => 255],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(),
                'targetAttribute' => ['patient_id' => 'id']
            ],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(),
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
            'message' => 'Message',
            'user_id' => 'User ID',
            'patient_id' => 'Patient ID',
            'response' => 'Response',
            'status' => 'Status',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'object_type' => 'Тип объекта',
            'object_id' => 'ID объекта',
            'request_data' => 'Детали запроса',
        ];
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

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getRecipient(): string
    {
        if ($this->object_type === 'reception') {
            $recipient = Reception::findOne($this->object_id);
            if (isset($recipient)) {
                return $recipient->patient ? $recipient->patient->getFullName() : '-';
            }
        }

        if ($this->object_type === 'transaction') {
            $recipient = Transaction::findOne($this->object_id);
            if (isset($recipient)) {
                return $recipient->patient ? $recipient->patient->getFullName() : '-';
            }
        }

        return '-';
    }

    public function checkStatus(): bool
    {
        return isset(self::STATUS[$this->status]);
    }

    public function getStatus(): string
    {
        if (!$this->checkStatus()) {
            return '-';
        }

        return $this->status === self::STATUS_SUCCESS
            ? '<p class="tex_green">' . self::STATUS[$this->status] . '</p>'
            : '<p class="tex_red">' . self::STATUS[$this->status] . '</p>';
    }
}
