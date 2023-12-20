<?php

namespace common\models\traits\Invoice;

use common\models\EmployeeSalary;
use common\models\InvoiceServices;
use common\models\Patient;
use common\models\Reception;
use common\models\Report;
use common\models\Transaction;
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
     * Gets query for [[InvoiceServices]].
     *
     * @return ActiveQuery
     */
    public function getInvoiceServices(): ActiveQuery
    {
        return $this->hasMany(InvoiceServices::class, ['invoice_id' => 'id']);
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
     * Gets query for [[Patient]].
     *
     * @return ActiveQuery
     */
    public function getAssistant(): ActiveQuery
    {
        return $this->hasOne(Patient::class, ['id' => 'assistant_id']);
    }

    /**
     * Gets query for [[Reception]].
     *
     * @return ActiveQuery
     */
    public function getReception(): ActiveQuery
    {
        return $this->hasOne(Reception::class, ['id' => 'reception_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEmployeeSalary(): ActiveQuery
    {
        return $this->hasMany(EmployeeSalary::class, ['invoice_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPayments(): ActiveQuery
    {
        return $this->hasMany(Transaction::class, ['invoice_id' => 'id'])->andOnCondition(
            ['type' => Transaction::TYPE_PAY]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getTransaction(): ActiveQuery
    {
        return $this->hasOne(Transaction::class, ['invoice_id' => 'id'])->andOnCondition(
            ['type' => Transaction::TYPE_PAY]
        );
    }

    public function getReports(): ActiveQuery
    {
        return $this->hasMany(Report::class, ['invoice_id' => 'id']);
    }
}

