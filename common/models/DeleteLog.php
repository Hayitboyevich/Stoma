<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "delete_log".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $object_type
 * @property int|null $object_id
 * @property string|null $object_data
 * @property string|null $deleted_at
 */
class DeleteLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'delete_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'object_id'], 'integer'],
            [['object_data'], 'string'],
            [['deleted_at'], 'safe'],
            [['name', 'object_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'object_type' => 'Object Type',
            'object_id' => 'Object ID',
            'object_data' => 'Object Data',
            'deleted_at' => 'Deleted At',
        ];
    }
}
