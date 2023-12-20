<?php

namespace common\models\traits\Reception;

use common\models\Invoice;
use common\models\Patient;
use common\models\PriceList;
use common\models\User;
use yii\db\ActiveQuery;

trait HasRelationships
{
    /**
     * Gets query for [[Doctor]].
     *
     * @return ActiveQuery
     */
    public function getDoctor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'doctor_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(PriceList::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return ActiveQuery
     */
    public function getPatient(): ActiveQuery
    {
        return $this->hasOne(Patient::class, ['id' => 'patient_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvoiceRelation(): ActiveQuery
    {
        return $this->hasOne(Invoice::class, ['reception_id' => 'id']);
    }
}
