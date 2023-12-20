<?php

namespace common\models\traits\Patient;

use common\models\Media;
use common\models\PatientExamination;
use common\models\Reception;
use common\models\Transaction;
use common\models\User;
use yii\db\ActiveQuery;

trait HasRelationships
{
    /**
     * @return ActiveQuery
     */
    public function getDoctor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'doctor_id']);
    }

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
    public function getVisits(): ActiveQuery
    {
        return $this->hasMany(Reception::class, ['patient_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getExaminations(): ActiveQuery
    {
        return $this->hasMany(PatientExamination::class, ['patient_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransactions(): ActiveQuery
    {
        return $this->hasMany(Transaction::class, ['patient_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCancelledVisits(): ActiveQuery
    {
        return $this->hasMany(Reception::class, ['patient_id' => 'id'])
            ->andWhere(['canceled' => self::DELETED])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(Media::class, ['object_id' => 'id'])
            ->andOnCondition(['object_type' => 'patient'])
            ->orderBy(['id' => SORT_DESC]);
    }
}
