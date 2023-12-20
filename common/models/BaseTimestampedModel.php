<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * @property int|null $created_at Время создания
 * @property int|null $updated_at Обновленное время
 * @property int|null $deleted_at Удаленное время
 **/
class BaseTimestampedModel extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return date('U'); // unix timestamp
                },
            ],
        ];
    }
}
