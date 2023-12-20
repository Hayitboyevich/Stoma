<?php

namespace common\models;

use yii\base\Model;
use yii\db\Query;

class ReportInvoice extends Model
{
    public $start_date;
    public $end_date;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->start_date = $config['start_date'];
        $this->end_date = $config['end_date'];
    }

    public function getVisits()
    {
        return Reception::find()
            ->where(['>=', 'record_date', date('Y-m-d', strtotime($this->start_date))])
            ->andWhere(['<=', 'record_date', date('Y-m-d', strtotime($this->end_date))])
            ->count();
    }

    public function getPaidInvoicesCount()
    {
        return Invoice::find()
            ->closed()
            ->betweenDates($this->start_date, $this->end_date)
            ->count();
    }

    public function getProfit()
    {
        $result = Report::find()->where([
            'and',
            ['>=', 'created_at', $this->start_date . ' 00:00:00'],
            ['<=', 'created_at', $this->end_date . ' 23:59:59']
        ])->sum('remains');

        return $result ?? 0;
    }

    public function getInvoicesTotal()
    {
        return (new Query())
            ->select(['COALESCE(SUM(invs.amount * invs.price_with_discount * invs.teeth_amount), 0) AS total'])
            ->from('invoice i')
            ->join('JOIN', 'invoice_services invs', 'i.id = invs.invoice_id')
            ->where(['!=', 'i.type', Invoice::TYPE_CANCELLED])
            ->andWhere(['i.preliminary' => Invoice::NOT_PRELIMINARY])
            ->andWhere([
                'and',
                ['>=', 'i.created_at', $this->start_date . ' 00:00:00'],
                ['<=', 'i.created_at', $this->end_date . ' 23:59:59']
            ])
            ->scalar();
    }

    public function getPaidInvoicesTotal()
    {
        $paidInvoices = InvoiceServices::find()
            ->select([
                'COALESCE(
                    SUM(invoice_services.amount * invoice_services.price_with_discount * invoice_services.teeth_amount),
                0) AS total'
            ])
            ->joinWith('invoice')
            ->where([
                'OR',
                ['invoice.type' => Invoice::TYPE_CLOSED],
                ['invoice.type' => Invoice::TYPE_DEBT, 'invoice.status' => Invoice::STATUS_PAID],
                ['invoice.type' => Invoice::TYPE_INSURANCE, 'invoice.status' => Invoice::STATUS_PAID],
            ])
            ->andWhere(['invoice.preliminary' => Invoice::NOT_PRELIMINARY])
            ->andWhere([
                'and',
                ['>=', 'invoice.created_at', $this->start_date . ' 00:00:00'],
                ['<=', 'invoice.created_at', $this->end_date . ' 23:59:59']
            ])
            ->scalar();

        $debtInvoices = Invoice::find()
            ->where([
                'OR',
                ['type' => Invoice::TYPE_DEBT, 'status' => Invoice::STATUS_UNPAID],
                ['type' => Invoice::TYPE_INSURANCE, 'status' => Invoice::STATUS_UNPAID],
            ])
            ->notPreliminary()
            ->betweenDates($this->start_date, $this->end_date)
            ->all();

        $debt = 0;
        foreach ($debtInvoices as $invoice) {
            $debt += $invoice->getInvoicePayTotal();
        }

        return $paidInvoices + $debt;
    }

    public function getDebt()
    {
        $debt = 0;
        $debtInvoices = Invoice::find()
            ->notPreliminary()
            ->notClosed()
            ->betweenDates($this->start_date, $this->end_date)
            ->all();

        foreach ($debtInvoices as $invoice) {
            if ($invoice->type === Invoice::TYPE_NEW) {
                $debt += $invoice->getInvoiceTotal();
            } else {
                $debt += $invoice->getRemains();
            }
        }

        return $debt;
    }

    public function getPayTotal(): array
    {
        $array = [
            'all' => 0,
            'cash' => 0,
            'card' => 0,
        ];

        $invoices = Invoice::find()
            ->notCancelled()
            ->notPreliminary()
            ->betweenDates($this->start_date, $this->end_date)
            ->with('payments')
            ->all();

        foreach ($invoices as $invoice) {
            foreach ($invoice->payments as $transaction) {
                if ($transaction->payment_method === 'cash') {
                    $array['cash'] += $transaction->amount;
                } else {
                    $array['card'] += $transaction->amount;
                }
                $array['all'] += $transaction->amount;
            }
        }

        return $array;
    }

    public function getSentSmsCount()
    {
        return SmsNotification::find()
            ->where(['>=', 'created_at', date('Y-m-d 00:00:00', strtotime($this->start_date))])
            ->andWhere(['<=', 'created_at', date('Y-m-d 23:59:59', strtotime($this->end_date))])
            ->count();
    }

    /**
     * @return bool|int|string|null
     */
    public function getInvoicesCount()
    {
        return Invoice::find()
            ->notCancelled()
            ->notPreliminary()
            ->betweenDates($this->start_date, $this->end_date)
            ->count();
    }

    public function getInsuranceInvoicesCount()
    {
        return Invoice::find()
            ->where(['type' => Invoice::TYPE_INSURANCE])
            ->notPreliminary()
            ->betweenDates($this->start_date, $this->end_date)
            ->count();
    }

    public function getInsuranceInvoicesTotal()
    {
        return InvoiceServices::find()
            ->select([
                'COALESCE(
                    SUM(invoice_services.amount * invoice_services.price_with_discount * invoice_services.teeth_amount),
                0) AS total'
            ])
            ->joinWith('invoice')
            ->where(['invoice.type' => Invoice::TYPE_INSURANCE, 'invoice.preliminary' => Invoice::NOT_PRELIMINARY])
            ->andWhere([
                'and',
                ['>=', 'invoice.created_at', $this->start_date . ' 00:00:00'],
                ['<=', 'invoice.created_at', $this->end_date . ' 23:59:59']
            ])
            ->scalar();
    }

    public function getAverageCheck()
    {
        $invoicesCount = $this->getInvoicesCount();
        return $invoicesCount != 0 ? $this->getInvoicesTotal() / $invoicesCount : 0;
    }

    public function getTotalBalance()
    {
        $array = [
            Transaction::TYPE_ADD_MONEY => 0,
            Transaction::TYPE_PAY => 0,
            Transaction::TYPE_WITHDRAW_MONEY => 0
        ];

        $transactions = Transaction::find()
            ->select(['type', 'SUM(amount) AS total'])
            ->groupBy(['type'])
            ->asArray()
            ->all();

        foreach ($transactions as $transaction) {
            $array[$transaction['type']] += $transaction['total'];
        }

        $diff = $array['add_money'] - $array['pay'] - $array['withdraw_money'];
        if ($diff >= 0) {
            return $diff;
        }

        return 0;
    }
}
