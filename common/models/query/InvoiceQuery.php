<?php

namespace common\models\query;

use common\models\Invoice;
use yii\db\ActiveQuery;

class InvoiceQuery extends ActiveQuery
{
    /**
     * @return InvoiceQuery
     */
    public function notCancelled(): InvoiceQuery
    {
        return $this->andWhere(['!=', 'type', Invoice::TYPE_CANCELLED]);
    }

    /**
     * @return InvoiceQuery
     */
    public function notPreliminary(): InvoiceQuery
    {
        return $this->andWhere(['preliminary' => Invoice::NOT_PRELIMINARY]);
    }

    /**
     * @return InvoiceQuery
     */
    public function notClosed(): InvoiceQuery
    {
        return $this->andWhere([
            'OR',
            ['type' => Invoice::TYPE_NEW],
            ['type' => Invoice::TYPE_INSURANCE, 'status' => Invoice::STATUS_UNPAID],
            ['type' => Invoice::TYPE_DEBT, 'status' => Invoice::STATUS_UNPAID],
        ]);
    }

    /**
     * @return InvoiceQuery
     */
    public function closed(): InvoiceQuery
    {
        return $this->andWhere([
            'OR',
            ['type' => Invoice::TYPE_CLOSED],
            ['type' => Invoice::TYPE_INSURANCE, 'status' => Invoice::STATUS_PAID],
            ['type' => Invoice::TYPE_DEBT, 'status' => Invoice::STATUS_PAID],
        ]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return InvoiceQuery
     */
    public function betweenDates(string $startDate, string $endDate): InvoiceQuery
    {
        return $this->andWhere([
            'and',
            ['>=', 'created_at', $startDate . ' 00:00:00'],
            ['<=', 'created_at', $endDate . ' 23:59:59'],
        ]);
    }
}
