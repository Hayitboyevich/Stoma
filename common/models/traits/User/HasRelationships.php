<?php

namespace common\models\traits\User;

use common\models\DoctorPercent;
use common\models\DoctorSchedule;
use common\models\EmployeeSalary;
use common\models\Media;
use common\models\PriceList;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

trait HasRelationships
{
    /**
     * @return ActiveQuery
     */
    public function getMedia(): ActiveQuery
    {
        return $this->hasOne(Media::class, ['id' => 'media_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSchedules(): ActiveQuery
    {
        return $this->hasMany(DoctorSchedule::class, ['doctor_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEarnings(): ActiveQuery
    {
        return $this->hasMany(EmployeeSalary::class, ['user_id' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(PriceList::class, ['id' => 'category_id'])
            ->viaTable('doctor_category', ['doctor_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAssistant(): ActiveQuery
    {
        return $this->hasOne(self::class, ['id' => 'assistant_id']);
    }

    /**
     * @return ActiveQuery
     */

    public function getPercents(): ActiveQuery
    {
        return $this->hasMany(DoctorPercent::class, ['user_id' => 'id']);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return ActiveQuery
     */
    public function getEarningsByDate(string $startDate, string $endDate): ActiveQuery
    {
        return $this->hasMany(EmployeeSalary::class, ['user_id' => 'id'])
            ->andWhere(['>=', 'created_at', $startDate . ' 00:00:00'])
            ->andWhere(['<=', 'created_at', $endDate . ' 23:59:59'])
            ->orderBy(['visit_time' => SORT_DESC]);
    }
}
