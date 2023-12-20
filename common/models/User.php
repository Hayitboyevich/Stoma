<?php

namespace common\models;

use common\models\traits\User\HasRelationships;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property string $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $firstname
 * @property string $lastname
 * @property string $phone
 * @property string $work_status
 * @property string $work_start_date
 * @property string $dob
 * @property string $passport
 * @property string $passport_issued
 * @property integer $media_id
 * @property integer $assistant_id
 * @property integer $chat_id
 * @property string $reset_password_code
 * @property string $reset_code_expire
 * @property int|null $old_id
 */
class User extends ActiveRecord implements IdentityInterface
{
    use HasRelationships;

    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    public const WORK_STATUS_AVAILABLE = 'available';
    public const WORK_STATUS_VACATION = 'vacation';
    public const WORK_STATUS_FIRED = 'fired';
    public const WORK_STATUS = [
        self::WORK_STATUS_AVAILABLE => 'Работает',
        self::WORK_STATUS_VACATION => 'В отпуске',
        self::WORK_STATUS_FIRED => 'Уволен',
        '' => 'Неизвестно',
    ];
    public const USER_ROLE = [
        'doctor' => 'Врач',
        'director' => 'Директор',
        'admin' => 'Админ',
        'assistant' => 'Ассистент',
        'reception' => 'Приёмная',
        'cashier' => 'Кассир',
        'accountant' => 'Бухгалтер',
        'technician' => 'Техник',
        'buyer' => 'Закупщик'
    ];

    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'required'],
            [['email', 'username'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            [['role', 'password', 'reset_code_expire', 'reset_password_code'], 'safe'],
            [['media_id', 'chat_id', 'assistant_id'], 'integer'],
            [
                [
                    'firstname',
                    'lastname',
                    'phone',
                    'work_status',
                    'work_start_date',
                    'dob',
                    'passport',
                    'passport_issued'
                ],
                'string'
            ],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function getFullName(): string
    {
        return $this->lastname . " " . $this->firstname;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(
            ['id' => $id, 'status' => self::STATUS_ACTIVE, 'work_status' => self::WORK_STATUS_AVAILABLE]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?User
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'phone' => 'Телефон',
            'chat_id' => 'Уникальный номер телеграм',
            'username' => 'Логин',
            'status' => 'Статус',
            'password' => 'Пароль',
            'role' => 'Роль',
            'old_id' => 'ID из старой базы',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($this->id);
        $auth->assign($auth->getRole($this->role), $this->id);

        parent::afterSave($insert, $changedAttributes);
    }

    public function assignRole($roleName)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        try {
            $auth->revokeAll($this->id);
            $auth->assign($role, $this->id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function getOnlyNumbers($phone)
    {
        return preg_replace("/[^0-9]/", "", $phone);
    }

    /**
     * @param $role
     * @return array
     */
    public static function getUsersByRole($role): array
    {
        return self::find()
            ->select('user.*')
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => $role])
            ->all();
    }

    /**
     * @return bool
     */
    public function resetCodeNotExpired(): bool
    {
        return static::find()->where(['id' => $this->id])
            ->andWhere(['>', 'reset_code_expire', new Expression('NOW()')])
            ->exists();
    }

    public static function getTotalEarnings($userId, $dateFrom, $dateTo)
    {
        $result = EmployeeSalary::find()
            ->where([
                'and',
                ['>=', 'created_at', $dateFrom . ' 00:00:00'],
                ['<=', 'created_at', $dateTo . ' 23:59:59']
            ])
            ->andWhere(['user_id' => $userId])
            ->sum('employee_earnings');

        return $result ?? 0;
    }

    public static function getAllTotalEarnings($dateFrom, $dateTo)
    {
        $result = EmployeeSalary::find()
            ->where([
                'and',
                ['>=', 'created_at', $dateFrom . ' 00:00:00'],
                ['<=', 'created_at', $dateTo . ' 23:59:59']
            ])
            ->sum('employee_earnings');

        return $result ?? 0;
    }

    public static function getCountPatients($userId, $dateFrom, $dateTo)
    {
        return EmployeeSalary::find()
            ->where([
                'and',
                ['>=', 'created_at', $dateFrom . ' 00:00:00'],
                ['<=', 'created_at', $dateTo . ' 23:59:59'],
                ['user_id' => $userId]
            ])
            ->count();
    }

    public function getActualScheduleId()
    {
        $userSchedule = DoctorSchedule::findOne(['doctor_id' => $this->id, 'current' => 1]);
        return isset($userSchedule) ? $userSchedule->id : false;
    }

    public static function getDoctorTodaySchedule($doctorId): string
    {
        $weekDay = strtolower(mb_substr(date('l'), 0, 3));
        $doctorSchedule = DoctorSchedule::find()
            ->where(['doctor_id' => $doctorId, 'current' => DoctorSchedule::ACTIVE_SCHEDULE])
            ->asArray()
            ->one();

        if (isset($doctorSchedule)) {
            $doctorScheduleItem = DoctorScheduleItem::find()
                ->where(['doctor_schedule_id' => $doctorSchedule['id'], 'weekday' => $weekDay])
                ->asArray()
                ->one();

            if (isset($doctorScheduleItem)) {
                return date('H:i', strtotime($doctorScheduleItem['time_from'])) . ' - ' . date(
                        'H:i',
                        strtotime($doctorScheduleItem['time_to'])
                    );
            }
        }

        return '';
    }

    public static function getCurrentStatistics(): array
    {
        $paid = 0;
        $unpaid = 0;

        $invoices = self::findRelevantInvoices();

        foreach ($invoices as $invoice) {
            $doctorEarnings = self::calculateDoctorEarnings($invoice);

            self::updatePaidAndUnpaidAmounts($invoice, $doctorEarnings, $paid, $unpaid);
        }

        return [
            'paid' => $paid,
            'unpaid' => $unpaid
        ];
    }

    private static function findRelevantInvoices(): array
    {
        return Invoice::find()
            ->notPreliminary()
            ->betweenDates(date('Y-m-d'), date('Y-m-d'))
            ->andWhere(['doctor_id' => Yii::$app->user->identity->id])
            ->andWhere([
                'OR',
                ['type' => Invoice::TYPE_NEW],
                ['type' => Invoice::TYPE_DEBT],
                ['type' => Invoice::TYPE_INSURANCE],
                ['type' => Invoice::TYPE_CLOSED]
            ])
            ->with('invoiceServices')
            ->all();
    }

    private static function calculateDoctorEarnings($invoice)
    {
        $doctorEarnings = 0;

        foreach ($invoice->invoiceServices as $invoiceService) {
            $dp = DoctorPercent::findOne([
                'user_id' => $invoice->doctor_id,
                'price_list_id' => $invoiceService->priceListItem->price_list_id
            ]);

            if ($dp) {
                $price = $invoiceService->price_with_discount * $invoiceService->amount * $invoiceService->teeth_amount;

                $doctorEarnings += ($price - $invoiceService->priceListItem->consumable) * $dp->percent / 100;
            }
        }

        return $doctorEarnings;
    }

    private static function updatePaidAndUnpaidAmounts($invoice, $doctorEarnings, &$paid, &$unpaid): void
    {
        if ($invoice->type === Invoice::TYPE_NEW) {
            $unpaid += $doctorEarnings;
        } elseif ($invoice->type === Invoice::TYPE_CLOSED) {
            $paid += $doctorEarnings;
        } elseif ($invoice->type === Invoice::TYPE_DEBT && $invoice->status === Invoice::STATUS_PAID) {
            $paid += $doctorEarnings;
        } elseif ($invoice->type === Invoice::TYPE_INSURANCE && $invoice->status === Invoice::STATUS_PAID) {
            $paid += $doctorEarnings;
        } elseif (
            ($invoice->type === Invoice::TYPE_DEBT || $invoice->type === Invoice::TYPE_INSURANCE)
            && $invoice->status === Invoice::STATUS_UNPAID
        ) {
            $paidSum = EmployeeSalary::find()
                ->where(['invoice_id' => $invoice->id, 'user_id' => Yii::$app->user->identity->id])
                ->sum('employee_earnings');

            if (!is_null($paidSum)) {
                $paid += $paidSum;
                $unpaid += $doctorEarnings - $paidSum;
            } else {
                $unpaid += $doctorEarnings;
            }
        }
    }
}
