<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/** @var $reception_operator User **/

/**
 * This is the model class for table "appointment_request".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $status
 * @property int|null $operator_id
 * @property int|null $patient_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $parent_first_name
 * @property string|null $parent_last_name
 * @property string|null $dob
 * @property string|null $created_at
 * @property string|null $source
 *
 * @property User $operator
 * @property Patient $patient
 */
class AppointmentRequest extends ActiveRecord
{
    public const SOURCE_LIVE_STOMA_BOT = 'live_stoma_bot';
    public const SOURCE_KID_SMILE_BOT = 'kidsmilebot';

    public const STATUS_NEW = 0; // Новый
    public const STATUS_NOT_RESPONDED = 1; // Не отвечен
    public const STATUS_PHONE_TURNOFF = 2; // Телефон не доступен
    public const STATUS_ACCEPTED = 3; // Принят
    public const STATUS_MADE_APPOINTMENT = 4; // Записан

    public const STATUS_LIST = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_NOT_RESPONDED => 'Не отвечен',
        self::STATUS_PHONE_TURNOFF => 'Телефон не доступен',
        self::STATUS_ACCEPTED => 'Принят',
        self::STATUS_MADE_APPOINTMENT => 'Записан'
    ];

    public const STATUS_COLOR = [
        self::STATUS_NEW => '#0047F9',
        self::STATUS_NOT_RESPONDED => '#7A28CB',
        self::STATUS_PHONE_TURNOFF => '#CC3030',
        self::STATUS_ACCEPTED => '#27A55D',
        self::STATUS_MADE_APPOINTMENT => '#FF6700'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'appointment_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['chat_id', 'status', 'operator_id', 'patient_id'], 'integer'],
            [['created_at', 'dob'], 'safe'],
            [['first_name', 'last_name', 'phone', 'parent_last_name', 'parent_first_name'], 'string', 'max' => 255],
            [
                ['operator_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['operator_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::class,
                'targetAttribute' => ['patient_id' => 'id']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'status' => 'Статус',
            'operator_id' => 'Оператор',
            'patient_id' => 'Пациент',
            'first_name' => 'Фамилия',
            'last_name' => 'Имя',
            'phone' => 'Телефон',
            'source' => 'Бот',
            'created_at' => 'Время'
        ];
    }

    public function getSource(): array
    {
        return [
            self::SOURCE_LIVE_STOMA_BOT => 'Live Stoma Bot',
            self::SOURCE_KID_SMILE_BOT => 'Kid Smile Bot',
        ];
    }

    public function getStatus(): array
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_ACCEPTED => 'Принят',
            self::STATUS_DRAFT => 'Черновик'
        ];
    }

    public function getSourceName(): string
    {
        return $this->getSource()[$this->source] ?? '';
    }

    /**
     * Gets query for [[Operator]].
     *
     * @return ActiveQuery
     */
    public function getOperator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'operator_id']);
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
     * @return void
     */
    public function notifyOperators(): void
    {
        $operators = User::find()
            ->join('INNER JOIN', 'auth_assignment', 'user.id = auth_assignment.user_id')
            ->where(['auth_assignment.item_name' => 'reception'])
            ->all();

        if (!empty($operators)) {
            $telegram = new Telegram(null, Yii::$app->params['operator_telegram_bot']);
            $url = Yii::$app->params['site_url'] . 'appointment-request/index';
            $new_appointment_request_text = "Новая <a href='{$url}'>заявка</a> на запись:
Имя: {$this->first_name} 
Фамилия: {$this->last_name} 
Телефон: {$this->phone} 
";
            foreach ($operators as $reception_operator) {
                if (!empty($reception_operator->chat_id)) {
                    $telegram->sendMessage(
                        [
                            'chat_id' => $reception_operator->chat_id,
                            'text' => $new_appointment_request_text,
                            'parse_mode' => 'HTML'
                        ]
                    );
                }
            }
        }
    }

    /**
     * @return bool|int|string|null
     */
    public static function getUnreadCount()
    {
        return static::find()->where(['status' => self::STATUS_NEW])->count();
    }
}
