<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "price_list".
 *
 * @property int $id
 * @property string $section Раздел
 * @property int $status Статус
 */
class PriceList extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'price_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['section'], 'required'],
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['section'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'section' => 'Раздел',
        ];
    }

    public function getItems(): ActiveQuery
    {
        return $this->hasMany(PriceListItem::class, ['price_list_id' => 'id']);
    }
}
