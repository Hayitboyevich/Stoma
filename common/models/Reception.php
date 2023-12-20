<?php

namespace common\models;

use common\models\traits\Reception\HasRelationships;
use Yii;

/**
 * This is the model class for table "reception".
 *
 * @property int $id
 * @property int $patient_id     Пациент
 * @property int $doctor_id      Доктор
 * @property int $category_id Отделение
 * @property string|null $comment        Комментарий
 * @property int $status         Просмотрено / Не просмотрено
 * @property int|null $created_at     Время создания
 * @property int|null $updated_at     Обновленное время
 * @property int|null $deleted_at     Удаленное время
 *
 * @property User $doctor
 * @property Patient $patient
 * @property string $record_date
 * @property string $record_time_from
 * @property string $record_time_to
 * @property string $sms
 * @property integer $duration
 * @property integer $canceled
 * @property string $cancel_reason
 * @property string $state
 */
class Reception extends BaseTimestampedModel
{
    use HasRelationships;

    public const CANCELED = 1;
    public const NOT_CANCELED = 0;

    public const WEEKDAYS = [
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'reception';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['patient_id', 'doctor_id'], 'required'],
            [
                [
                    'patient_id',
                    'doctor_id',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'category_id',
                    'duration',
                    'canceled'
                ],
                'integer'
            ],
            [
                ['comment', 'record_date', 'record_time_from', 'record_time_to', 'sms', 'cancel_reason', 'state'],
                'string'
            ],
            [
                ['doctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['doctor_id' => 'id']
            ],
            [
                ['patient_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Patient::className(),
                'targetAttribute' => ['patient_id' => 'id']
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
            'patient_id' => 'Пациент',
            'doctor_id' => 'Доктор',
            'comment' => 'Комментарий',
            'status' => 'Просмотрено / Не просмотрено',
            'created_at' => 'Время создания',
            'updated_at' => 'Обновленное время',
            'deleted_at' => 'Удаленное время',
            'category_id' => 'Отделение',
            'record_date' => 'Дата записи',
            'record_time_from' => 'Время начала приема',
            'record_time_to' => 'Время окончания приема',
            'sms' => 'SMS УВЕДОМЛЕНИЕ',
            'duration' => 'Продолжительность',
            'canceled' => 'Отменен',
            'cancel_reason' => 'Причина отказа',
            'state' => 'Статус'
        ];
    }

    public function getStatus(): array
    {
        return [
            'patient_came' => 'Пациент пришел',
            'admission_started' => 'Прием начался',
            'scheduled' => 'Запланированный',
            'admission_finished' => 'Прием закончен'
        ];
    }

    public function getStatusClass(): array
    {
        return [
            'patient_came' => 'orange',
            'admission_started' => 'blue',
            'scheduled' => 'pink',
            'admission_finished' => 'green'
        ];
    }

    public function getClasses(): array
    {
        return [
            'patient_came' => 'btn__yellow',
            'admission_started' => 'btn__blue',
            'scheduled' => 'btn__pink',
            'admission_finished' => 'btn__green'
        ];
    }

    public static function getScheduleRecords($data)
    {
        $selectedDoctorId = isset($data['selected_doctor_id']) && $data['selected_doctor_id'] != 0
            ? $data['selected_doctor_id']
            : null;
        $date = isset($data['date']) ? date("Y-m-d", strtotime($data['date'])) : date("Y-m-d");

        $where = [];
        if ($selectedDoctorId !== null) {
            $where[] = 'r.doctor_id = :doctor_id';
        }
        $where[] = 'r.record_date = :date';
        $where[] = 'r.canceled = ' . self::NOT_CANCELED;

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
            SELECT 
                pl.section,
                pl.id AS section_id,
                r.doctor_id, 
                r.category_id, 
                u.lastname, 
                u.firstname, 
                m.file_type,
                m.id AS file_id,
                GROUP_CONCAT(r.id) AS record_ids 
            FROM reception AS r 
            LEFT JOIN price_list AS pl ON r.category_id = pl.id 
            LEFT JOIN user AS u ON r.doctor_id = u.id
            LEFT JOIN media AS m ON m.id = u.media_id
            {$whereSql}
            GROUP BY 
                r.doctor_id,
                r.category_id
            ORDER BY r.category_id
        ";

        $params = [':date' => $date];
        if ($selectedDoctorId !== null) {
            $params[':doctor_id'] = $selectedDoctorId;
        }

        return Yii::$app->db->createCommand($sql)->bindValues($params)->queryAll();
    }


    /**@val array $data
     * $on_the_day_notifications = Reception::getRecords(['date' => 'CURDATE()','day' => 'on_the_day']);
     * $day_before_notifications = Reception::getRecords(['date' => 'DATE_SUB(CURDATE(),INTERVAL -1 DAY)','day' => 'day_before']);
     */
    public static function getRecords($data)
    {
        $sql = "
            SELECT 
                r.*,
                tn.object_type,
                tn.object_id,
                u.firstname AS doc_firstname,
                u.lastname AS doc_lastname,
                p.chat_id,
                p.firstname AS patient_firstname,
                p.lastname AS patient_lastname
            FROM `reception` AS r 
            LEFT JOIN telegram_notification AS tn 
            ON (tn.object_id = r.id AND tn.object_type LIKE 'reception') 
            LEFT JOIN patient AS p ON p.id = r.patient_id 
            LEFT JOIN user AS u ON u.id = r.doctor_id 
            WHERE object_id IS NULL 
            AND record_date = {$data['date']} 
            AND sms LIKE '{$data['day']}'
            AND p.deleted = 0
            AND r.canceled = 0
        ";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @return bool
     */
    public function saveDeleteLog(): bool
    {
        $model = self::find()
            ->joinWith('doctor')
            ->joinWith('patient')
            ->joinWith('category')
            ->where(['reception.id' => $this->id])
            ->asArray()
            ->one();

        $deleteLog = new DeleteLog();
        $deleteLog->user_id = Yii::$app->user->identity->id;
        $deleteLog->object_type = 'reception';
        $deleteLog->object_id = $this->id;
        $deleteLog->object_data = json_encode($model);
        $deleteLog->name = Yii::$app->user->identity->firstname . ' ' . Yii::$app->user->identity->lastname;
        return $deleteLog->save();
    }

    /**
     * @return string[]
     */
    public function sendSmsNotification(): array
    {
        if (strtotime($this->record_date . ' ' . $this->record_time_from) < time()) {
            return ['status' => 'fail', 'message' => 'record time passed'];
        }

        $sms_gateway = new SmsGateway();
        $sms_gateway->text = SmsGateway::formatSms([
            'template' => 'remind_sms',
            'data' => [
                'fullname' => $this->patient->lastname . ' ' . $this->patient->firstname,
                'time' => $this->record_time_from,
                'date' => $this->record_date,
                'doctor' => $this->doctor->lastname . ' ' . $this->doctor->firstname,
            ]
        ]);
        $sms_gateway->recipient = $this->patient->phone;
        $response = $sms_gateway->sendSms();

        $smsNotification = SmsNotification::findOne($response['log_id']);

        $smsNotification->object_type = 'reception';
        $smsNotification->object_id = $this->id;
        $smsNotification->save();


        $smsNotification->response = $response;
        $smsNotification->save();
        $parsed_response = json_decode($response['response'], true);

        if (empty($parsed_response['error'])) {
            $output = ['status' => 'success', 'message' => 'sms_sent'];
        } else {
            $output = [
                'status' => 'fail',
                'message' => $parsed_response['error']['code'] . ' ' . $parsed_response['error']['message']
            ];
        }
        return $output;
    }

    /**
     * @return array|string[]
     */
    public function sendTelegramNotification(): array
    {
        if (empty($this->patient->chat_id)) {
            return ['status' => 'fail', 'message' => 'chat_id empty'];
        }

        if (strtotime($this->record_date . ' ' . $this->record_time_from) < time()) {
            return ['status' => 'fail', 'message' => 'record time passed'];
        }

        $telegramNotification = new TelegramNotification();
        $telegramNotification->object_type = 'reception';
        $telegramNotification->object_id = $this->id;
        $telegramNotification->message = SmsGateway::formatSms([
            'template' => 'remind_sms',
            'data' => [
                'fullname' => $this->patient->lastname . ' ' . $this->patient->firstname,
                'time' => $this->record_time_from,
                'date' => $this->record_date,
                'doctor' => $this->doctor->lastname . ' ' . $this->doctor->firstname,
            ]
        ]);
        $telegramNotification->chat_id = $this->patient->chat_id;
        $telegramNotification->user_id = Yii::$app->user->identity->id;
        if ($telegramNotification->save()) {
            $telegram = new Telegram();
            $response = $telegram->sendMessage(
                ['chat_id' => $this->patient->chat_id, 'text' => $telegramNotification->message]
            );
            $telegramNotification->response = $response;
            $parsed_response = json_decode($response);
            $telegramNotification->save();
            if ($parsed_response && $parsed_response->ok) {
                $output = ['status' => 'success', 'message' => 'success'];
            } else {
                $output = [
                    'status' => 'fail',
                    'message' => $parsed_response['response']
                        ? $parsed_response['response']->message
                        : 'сообщение не отправлено, неизвестная ошибка!'
                ];
            }
        } else {
            $output = ['status' => 'fail', 'message' => print_r($telegramNotification->errors, true)];
        }

        return $output;
    }

    public function getInvoice(): ?Invoice
    {
        return Invoice::findOne(['reception_id' => $this->id]);
    }

    public static function getRecordsByDoctor($data)
    {
        $date = date("Y-m-d", strtotime($data['date']));

        $sql = "
SELECT 
       r.doctor_id,  
       u.lastname, 
       u.firstname, 
       m.file_type,
       m.id AS file_id,
       GROUP_CONCAT(r.id) AS record_ids 
FROM reception AS r 
LEFT JOIN price_list AS pl ON r.category_id = pl.id 
LEFT JOIN user AS u ON r.doctor_id = u.id
LEFT JOIN media AS m ON m.id = u.media_id
WHERE r.doctor_id = {$data['doctor_id']}
AND r.record_date LIKE '{$date}'
AND r.canceled = 0
GROUP BY 
         r.doctor_id
         ";

        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function getRecordsByDoctorWeek($data)
    {
        $sql = "
SELECT 
       r.doctor_id, 
       u.lastname, 
       u.firstname, 
       m.file_type,
       m.id AS file_id,
       GROUP_CONCAT(r.id) AS record_ids 
FROM reception AS r 
LEFT JOIN price_list AS pl ON r.category_id = pl.id 
LEFT JOIN user AS u ON r.doctor_id = u.id
LEFT JOIN media AS m ON m.id = u.media_id
WHERE r.doctor_id = {$data['doctor_id']}
AND r.record_date BETWEEN '{$data['start']}' AND '{$data['end']}'
AND r.canceled = 0
GROUP BY 
         r.doctor_id
         ";
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function getCurrentDateRecords($data)
    {
        $sql = "
SELECT 
       r.doctor_id, 
       u.lastname, 
       u.firstname, 
       m.file_type,
       m.id AS file_id,
       GROUP_CONCAT(r.id) AS record_ids 
FROM reception AS r 
LEFT JOIN price_list AS pl ON r.category_id = pl.id 
LEFT JOIN user AS u ON r.doctor_id = u.id
LEFT JOIN media AS m ON m.id = u.media_id
WHERE r.doctor_id = {$data['doctor_id']}
AND r.record_date = '{$data['date']}'
AND r.canceled = 0
GROUP BY 
         r.doctor_id
         ";
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public function getWeekRecords($startDate = null, $endDate = null, $doctorId = 0): array
    {
        $records = self::find()
            ->where(['canceled' => self::NOT_CANCELED])
            ->with(['doctor', 'patient'])
            ->orderBy(['record_date' => SORT_ASC]);

        if ($startDate && $endDate) {
            $records->andWhere(['between', 'record_date', $startDate, $endDate]);
        } else {
            $records->andWhere(
                [
                    'between',
                    'record_date',
                    date('Y-m-d', strtotime('monday this week')),
                    date('Y-m-d', strtotime('sunday this week'))
                ]
            );
        }

        if ($doctorId != 0) {
            $records->andWhere(['doctor_id' => $doctorId]);
        }

        $records = $records->all();

        $weekDates = $this->getWeekDates($startDate);

        $recordsByDoctor = [];
        foreach ($records as $record) {
            if (!isset($recordsByDoctor[$record->doctor_id])) {
                $recordsByDoctor[$record->doctor_id]['doctor']['name'] = $record->doctor ? $record->doctor->getFullName(
                ) : 'Врач не найден';
                $recordsByDoctor[$record->doctor_id]['doctor']['image'] = $record->doctor && !empty($record->doctor->media_id)
                    ? '/media/download?id=' . $record->doctor->media->id . '.' . $record->doctor->media->file_type
                    : '/img/default-avatar.png';
                foreach ($weekDates as $weekDate) {
                    $recordsByDoctor[$record->doctor_id][$weekDate] = [];
                }
            }
            $recordsByDoctor[$record->doctor_id][$record->record_date][] = $record;
        }

        return $recordsByDoctor;
    }

    public function getWeekDates($startDate = null): array
    {
        $weekDates = [];
        for ($i = 0; $i < 6; $i++) {
            $weekDates[] = date(
                'Y-m-d',
                strtotime(!empty($startDate) ? $startDate : 'monday this week') + $i * 24 * 60 * 60
            );
        }

        return $weekDates;
    }
}
