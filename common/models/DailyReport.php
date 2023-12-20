<?php

namespace common\models;

use Yii;

class DailyReport extends \yii\base\Model
{

    public $date;

    function __construct($config = [])
    {
        parent::__construct($config);
        $this->date = $config['date'];
    }


    public function getVisits()
    {
        return Reception::find()->where(['record_date' => date('Y-m-d', strtotime($this->date))])->count();
    }

    public function getNewPatients()
    {
        return Patient::find()->where("DATE(created_at) = DATE('{$this->date}')")->count();
    }

    public function getInvoices()
    {
        return Invoice::find()->where("DATE(created_at) = DATE('{$this->date}')")->count();
    }

    public function getPaidInvoices()
    {
        return Invoice::find()->where("DATE(created_at) = DATE('{$this->date}')")->andWhere(['status' => 'paid']
        )->count();
    }

    public function getCancelledInvoices()
    {
        return Invoice::find()->where("DATE(created_at) = DATE('{$this->date}')")->andWhere(['status' => 'cancelled']
        )->count();
    }

    public function getInvoicesTotal()
    {
        $sql = "SELECT 
SUM(invs.price) AS total
FROM `invoice` AS i 
JOIN invoice_services AS invs ON i.id = invs.invoice_id 
WHERE 
DATE(i.created_at) = DATE('{$this->date}') ";
        return Yii::$app->db->createCommand($sql)->queryOne()['total'];
    }

    public function getIncomingTotal()
    {
        $sql = "SELECT SUM(amount) AS total FROM `transaction` WHERE type LIKE 'pay' AND DATE(created_at) = DATE('{$this->date}')";
        return Yii::$app->db->createCommand($sql)->queryOne()['total'];
    }
}
