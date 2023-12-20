<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "price_list_item".
 *
 * @property int $id
 * @property int $price_list_id Раздел
 * @property int $technician_price_list_id Услуга течника
 * @property string $name Группы и позиции
 * @property int|null $parent_id
 * @property int $price Прайс
 * @property float $consumable Расходный материал
 * @property float $utilities Коммунальные услуги
 * @property float $amortization Амортизация оборудования и обслуживание
 * @property float $marketing Маркетинг
 * @property float $other_expenses Прочие расходы
 * @property bool $discount_apply Применять скидку
 * @property bool $is_group Группа
 * @property int $status Статус
 *
 * @property PriceListItem $parent
 * @property PriceList $priceList
 * @property PriceListItem[] $priceListItems
 */
class PriceListItem extends ActiveRecord
{
    public const DEFAULT_PERCENT = 0.5;
    public const MARKETING_PERCENT = 1.5;
    public const OTHER_EXPENSES_PERCENT = 13;
    public const AMORTIZATION_PERCENT = 2;

    public const DISCOUNT_APPLY = 1;
    public const DISCOUNT_NOT_APPLY = 0;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public const IS_GROUP = 1;
    public const IS_NOT_GROUP = 0;

    const SCENARIO_IS_GROUP = 'is_group';
    const SCENARIO_IS_NOT_GROUP = 'is_not_group';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'price_list_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['price_list_id'], 'required'],
            [['name', 'price', 'consumable'], 'required', 'on' => self::SCENARIO_IS_NOT_GROUP],
            [['price_list_id', 'parent_id', 'price', 'technician_price_list_id', 'status'], 'integer'],
            [
                'consumable',
                'compare',
                'compareAttribute' => 'price',
                'operator' => '<',
                'on' => self::SCENARIO_IS_NOT_GROUP
            ],
            [['consumable'], 'integer', 'min' => 0],
            [['name'], 'string', 'max' => 255],
            [['discount_apply', 'is_group'], 'boolean'],
            ['price', 'default', 'value' => 0],
            ['discount_apply', 'default', 'value' => true],
            ['is_group', 'default', 'value' => false],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [
                ['parent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => self::class,
                'targetAttribute' => ['parent_id' => 'id']
            ],
            [
                ['price_list_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PriceList::class,
                'targetAttribute' => ['price_list_id' => 'id']
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
            'price_list_id' => 'Раздел',
            'name' => 'Название',
            'parent_id' => 'Родительская категория',
            'price' => 'Цена',
            'section' => 'Категория',
            'consumable' => 'Расходный материал',
            'utilities' => 'Коммунальные услуги',
            'amortization' => 'Амортизация оборудования и обслуживание',
            'marketing' => 'Маркетинг',
            'other_expenses' => 'Прочие расходы',
            'technician_price_list_id' => 'Услуга техника',
            'discount_apply' => 'Применять скидку',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            $this->utilities = self::DEFAULT_PERCENT;
            $this->amortization = self::DEFAULT_PERCENT;
            $this->marketing = self::MARKETING_PERCENT;
            $this->other_expenses = self::OTHER_EXPENSES_PERCENT;
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return ActiveQuery
     */
    public function getParent(): ActiveQuery
    {
        return $this->hasOne(__CLASS__, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[PriceList]].
     *
     * @return ActiveQuery
     */
    public function getPriceList(): ActiveQuery
    {
        return $this->hasOne(PriceList::class, ['id' => 'price_list_id']);
    }

    /**
     * Gets query for [[PriceListItems]].
     *
     * @return ActiveQuery
     */
    public function getPriceListItems(): ActiveQuery
    {
        return $this->hasMany(self::class, ['parent_id' => 'id'])
            ->onCondition(['status' => self::STATUS_ACTIVE, 'is_group' => self::IS_NOT_GROUP]);
    }

    /**
     * Gets query for [[TechnicianPriceList]].
     *
     * @return ActiveQuery
     */
    public function getTechnicianPriceList(): ActiveQuery
    {
        return $this->hasOne(TechnicianPriceList::class, ['id' => 'technician_price_list_id']);
    }

    public function checkDiscountApply(): bool
    {
        return $this->discount_apply && $this->price > 0;
    }
}
