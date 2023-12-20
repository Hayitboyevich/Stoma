<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "old_price".
 *
 * @property int $id
 * @property int|null $section_code КОД РАЗДЕЛА
 * @property string|null $section РАЗДЕЛ
 * @property string|null $group ГРУППА
 * @property int|null $position_code КОД ПОЗИЦИИ
 * @property string|null $position ПОЗИЦИЯ
 * @property int|null $price ЦЕНА
 */
class OldPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'old_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_code', 'position_code', 'price'], 'integer'],
            [['section', 'group', 'position'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_code' => 'Section Code',
            'section' => 'Section',
            'group' => 'Group',
            'position_code' => 'Position Code',
            'position' => 'Position',
            'price' => 'Price',
        ];
    }
}
