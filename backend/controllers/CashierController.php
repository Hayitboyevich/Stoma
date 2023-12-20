<?php

namespace backend\controllers;

use common\models\Invoice;
use common\models\Reception;
use common\models\Transaction;
use common\models\TransferMoney;
use common\models\User;
use common\models\WithdrawMoney;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class CashierController extends Controller
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        ['actions' => ['stats', 'dashboard'], 'allow' => true, 'roles' => ['cashier_stats']],
                        ['actions' => ['patient'], 'allow' => true, 'roles' => ['cashier_patient_index']],
                        ['actions' => ['report'], 'allow' => true, 'roles' => ['view_report']],
                        [
                            'actions' => ['transfer-money-history', 'transfer-money'],
                            'allow' => true,
                            'roles' => ['transfer_money']
                        ],
                        ['actions' => ['withdraw-money'], 'allow' => true, 'roles' => ['withdraw_money']],
                        [
                            'actions' => ['patient-debts', 'excel-patient-debts'],
                            'allow' => true,
                            'roles' => ['patient_debts']
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function actionStats(): string
    {
        $get = Yii::$app->request->get();

        $transactions = Transaction::find()
            ->with(['patient'])
            ->orderBy(['id' => SORT_DESC]);

        if (Yii::$app->user->can('cashier')) {
            $transactions->andWhere(['user_id' => Yii::$app->user->id]);
        }

        $data['total_rows'] = $transactions->count();
        $data['per_page'] = (int)($get['per_page'] ?? 10);
        $data['page'] = (int)($get['page'] ?? 1);

        $data['offset'] = ($data['page'] - 1) * $data['per_page'];
        $data['total_pages'] = ceil($data['total_rows'] / $data['per_page']);
        $data['last_row_number'] = min($data['offset'] + $data['per_page'], $data['total_rows']);

        $data['transactions'] = $transactions
            ->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || isset($get['per_page']);

        return $this->render('stats', [
            'data' => $data
        ]);
    }

    public function actionDashboard(): string
    {
        $urlStartDate = Yii::$app->request->get('start_date');
        $urlEndDate = Yii::$app->request->get('end_date');
        $data['start_date'] = $urlStartDate ?? (new \DateTime())->format('Y-m-d');
        $data['end_date'] = $urlEndDate ?? (new \DateTime())->format('Y-m-d');
        $data['statistics'] = Transaction::getCashierStatistics($data);

        $model = User::findOne(Yii::$app->user->identity->id);

        return $this->render('dashboard', [
            'model' => $model,
            'data' => $data
        ]);
    }

    public function actionPatient(): string
    {
        $records = Reception::find()->where([
            'and',
            ['canceled' => Reception::NOT_CANCELED],
            ['>=', 'record_date', date('Y-m-d')],
            ['<=', 'record_date', date('Y-m-d')]
        ])->with(['patient', 'doctor', 'invoiceRelation'])->all();

        return $this->render('patient', [
            'records' => $records
        ]);
    }

    public function actionReport(): string
    {
        $get = Yii::$app->request->get();

        $data['start_date'] = Yii::$app->request->get('start_date', date('Y-m-d'));
        $data['end_date'] = Yii::$app->request->get('end_date', date('Y-m-d'));

        $invoiceSum = 0;
        $invoicePendingSum = 0;
        $patientCount = [];
        $invoices = Invoice::find()
            ->notCancelled()
            ->notPreliminary()
            ->betweenDates($data['start_date'], $data['end_date'])
            ->with('transaction')
            ->orderBy(['created_at' => SORT_DESC]);

        $invoiceQuery = $invoices->all();

        foreach ($invoiceQuery as $invoice) {
            $invoiceTotal = $invoice->getInvoiceTotal();
            $invoiceSum += $invoiceTotal;

            if (($invoice->type === Invoice::TYPE_DEBT && $invoice->status === Invoice::STATUS_UNPAID) || ($invoice->type == Invoice::TYPE_INSURANCE && $invoice->status === Invoice::STATUS_UNPAID) || $invoice->type === Invoice::TYPE_NEW) {
                if ($invoice->type === Invoice::TYPE_NEW) {
                    $invoicePendingSum += $invoiceTotal;
                } else {
                    $invoicePendingSum += $invoice->getRemains();
                }
            }

            $patientCount[$invoice->patient_id] = $invoice->patient_id;
        }

        $invoicePaidCount = array_filter($invoiceQuery, static function ($invoice) {
            return $invoice['type'] == Invoice::TYPE_CLOSED || ($invoice['type'] == Invoice::TYPE_INSURANCE && $invoice['status'] == Invoice::STATUS_PAID) || ($invoice['type'] == Invoice::TYPE_DEBT && $invoice['status'] == Invoice::STATUS_PAID);
        });

        $total_rows = $invoices->count();
        $data['total_rows'] = $total_rows;
        $data['per_page'] = array_key_exists('per_page', $get) ? $get['per_page'] : 10;
        $data['page'] = array_key_exists('page', $get) ? $get['page'] : 1;

        $data['offset'] = $data['per_page'] * ($data['page'] - 1);

        if ($data['per_page'] >= $data['total_rows']) {
            $data['total_pages'] = 1;
        } else {
            $add = 0;
            if ($data['total_rows'] % $data['per_page'] > 0) {
                $add = 1;
            }
            $data['total_pages'] = round($data['total_rows'] / $data['per_page']) + $add;
        }

        $data['invoices'] = $invoices
            ->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();

        $data['last_row_number'] = $data['offset'] + $data['per_page'] * $data['page'];

        if ($data['last_row_number'] > $data['total_rows']) {
            $data['last_row_number'] = $data['total_rows'];
        }

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || array_key_exists('per_page', $get);

        $invoiceIds = ArrayHelper::getColumn($invoicePaidCount, 'id');
        $invoicePaidSum = Transaction::find()
            ->where(['type' => Transaction::TYPE_PAY, 'invoice_id' => $invoiceIds])
            ->sum('amount');

        return $this->render('report', [
            'invoiceCount' => $total_rows,
            'invoicePaidCount' => count($invoicePaidCount),
            'invoicePaidSum' => $invoicePaidSum ?? 0,
            'invoiceSum' => $invoiceSum,
            'invoicePendingSum' => $invoicePendingSum,
            'patientCount' => count($patientCount),
            'data' => $data
        ]);
    }

    public function actionTransferMoneyHistory(): string
    {
        $transactions = Transaction::find()
            ->with(['patient', 'user', 'receptionPatient'])
            ->where(['is_transfer' => Transaction::IS_TRANSFER])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('transaction', [
            'transactions' => $transactions
        ]);
    }

    public function actionTransferMoney(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new TransferMoney();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $transactionPay = new Transaction();
                $transactionPay->patient_id = $model->sender_patient_id;
                $transactionPay->recipient_patient_id = $model->recipient_patient_id;
                $transactionPay->user_id = Yii::$app->user->identity->id;
                $transactionPay->amount = $model->amount;
                $transactionPay->payment_method = Transaction::NOT_PAYMENT_METHOD;
                $transactionPay->is_transfer = Transaction::IS_TRANSFER;
                $transactionPay->type = Transaction::TYPE_PAY;

                $transactionAddMoney = new Transaction();
                $transactionAddMoney->patient_id = $model->recipient_patient_id;
                $transactionAddMoney->recipient_patient_id = $model->sender_patient_id;
                $transactionAddMoney->user_id = Yii::$app->user->identity->id;
                $transactionAddMoney->amount = $model->amount;
                $transactionAddMoney->payment_method = Transaction::NOT_PAYMENT_METHOD;
                $transactionAddMoney->is_transfer = Transaction::IS_TRANSFER;
                $transactionAddMoney->type = Transaction::TYPE_ADD_MONEY;

                if (!$transactionPay->save()) {
                    throw new \Exception (implode("<br/>", ArrayHelper::getColumn($transactionPay->errors, 0, false)));
                }
                if (!$transactionAddMoney->save()) {
                    throw new \Exception (
                        implode("<br/>", ArrayHelper::getColumn($transactionAddMoney->errors, 0, false))
                    );
                }

                $transaction->commit();
                return ['status' => 'success'];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['status' => 'fail', 'message' => $e->getMessage()];
            }
        }

        return ['status' => 'fail', 'message' => implode("<br/>", ArrayHelper::getColumn($model->errors, 0, false))];
    }

    /**
     * @return array|string[]
     */
    public function actionWithdrawMoney(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new WithdrawMoney();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $transactionWithdrawMoney = new Transaction();
            $transactionWithdrawMoney->patient_id = $model->patient_id;
            $transactionWithdrawMoney->user_id = Yii::$app->user->identity->id;
            $transactionWithdrawMoney->amount = $model->amount;
            $transactionWithdrawMoney->cancel_reason = $model->reason;
            $transactionWithdrawMoney->payment_method = Transaction::NOT_PAYMENT_METHOD;
            $transactionWithdrawMoney->is_transfer = Transaction::IS_NOT_TRANSFER;
            $transactionWithdrawMoney->type = Transaction::TYPE_WITHDRAW_MONEY;
            if ($transactionWithdrawMoney->save()) {
                return ['status' => 'success'];
            }

            return [
                'status' => 'fail',
                'message' => implode("<br/>", ArrayHelper::getColumn($transactionWithdrawMoney->errors, 0, false))
            ];
        }

        return ['status' => 'fail', 'message' => implode("<br/>", ArrayHelper::getColumn($model->errors, 0, false))];
    }

    public function actionPatientDebts(): string
    {
        $startDate = Yii::$app->request->get('start_date', date('Y-m-d'));
        $endDate = Yii::$app->request->get('end_date', date('Y-m-d'));

        $invoices = $this->getInvoices($startDate, $endDate);

        return $this->render('patient-debts', [
            'invoices' => $invoices,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function actionExcelPatientDebts(string $startDate, string $endDate): string
    {
        $this->layout = 'excel_blank';

        $invoices = $this->getInvoices($startDate, $endDate);

        return $this->render('export-patient-debts', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    private function getInvoices(string $startDate, string $endDate): array
    {
        return Invoice::find()
            ->notPreliminary()
            ->notClosed()
            ->betweenDates($startDate, $endDate)
            ->with(['patient', 'reception', 'doctor'])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }
}
