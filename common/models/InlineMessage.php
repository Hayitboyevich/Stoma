<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "inline_message".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int $message_id
 * @property string|null $message_data
 * @property string|null $created_at
 */
class InlineMessage extends ActiveRecord
{

    public $message_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'inline_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['chat_id'], 'integer'],
            [['message_data'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'chat_id' => Yii::t('app', 'Chat ID'),
            'message_data' => Yii::t('app', 'Message Data'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->message_id = $this->getMessageID();
    }

    public function getMessageId()
    {
        $res = json_decode($this->message_data);
        return $res->result->message_id;
    }
}
