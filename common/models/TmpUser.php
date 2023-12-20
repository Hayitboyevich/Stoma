<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tmp_user".
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $phone
 * @property string|null $chat_id
 * @property string|null $username
 * @property string|null $dob
 * @property string|null $created_at
 */
class TmpUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tmp_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['firstname', 'lastname', 'phone', 'chat_id', 'username', 'dob'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'phone' => 'Phone',
            'chat_id' => 'Chat ID',
            'username' => 'Username',
            'dob' => 'Dob',
            'created_at' => 'Created At',
        ];
    }
}
