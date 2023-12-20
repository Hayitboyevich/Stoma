<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "config".
 *
 * @property string $key
 * @property string|null $value
 */
class Config extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['key'], 'required'],
            [['key', 'value'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'key' => 'Ключ',
            'value' => 'Значение',
        ];
    }
}
