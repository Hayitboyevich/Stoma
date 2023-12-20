<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "technician_price_list".
 *
 * @property int $id
 * @property string $name
 * @property int $price Прайс
 * @property int|null $status
 */
class TechnicianPriceList extends ActiveRecord
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'technician_price_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'price'], 'required'],
            [['price', 'status'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_INACTIVE],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'status' => 'Status',
        ];
    }
}
