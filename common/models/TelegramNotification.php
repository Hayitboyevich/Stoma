<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "telegram_notification".
 *
 * @property int $id
 * @property string $message
 * @property int|null $user_id
 * @property int|null $patient_id
 * @property string|null $response
 * @property string|null $status
 * @property int $chat_id
 * @property string|null $created_at
 * @property string|null $object_type
 * @property integer|null $object_id
 *
 * @property Patient $patient
 * @property User $user
 */
class TelegramNotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'chat_id'], 'required'],
            [['message', 'response','object_type'], 'string'],
            [['user_id', 'patient_id', 'chat_id','object_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'user_id' => 'User ID',
            'patient_id' => 'Patient ID',
            'response' => 'Response',
            'status' => 'Status',
            'chat_id' => 'Chat ID',
            'created_at' => 'Created At',
            'object_type' => 'Тип объекта',
            'object_id' => 'ID объекта',
        ];
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function newRecord($data){
        $telegramNotification = new TelegramNotification();
        $telegramNotification->message = $data['message'];
        $telegramNotification->patient_id = $data['notification']['patient_id'];
        $telegramNotification->response = $data['response'];
        $telegramNotification->chat_id = $data['notification']['chat_id'];
        $telegramNotification->object_type = 'reception';
        $telegramNotification->object_id = $data['notification']['id'];
        $parsed_response = json_decode($data['response']);
        if($parsed_response->ok){
            $telegramNotification->status = 'ok';
        }
        else{
            $telegramNotification->status = 'fail';
        }
        return $telegramNotification->save();
    }
}
