<?php

namespace backend\controllers;

use common\models\constants\UserRole;
use common\models\DeleteLog;
use common\models\EmployeeSalary;
use common\models\Invoice;
use common\models\InvoiceServices;
use common\models\Patient;
use common\models\PriceListItem;
use common\models\Report;
use common\models\Transaction;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        $this->layout = 'native-main';
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        ['actions' => ['ajax-create'], 'allow' => true, 'roles' => ['invoice_ajax_create']],
                        ['actions' => ['pay'], 'allow' => true, 'roles' => ['invoice_pay']],
                        ['actions' => ['cancel'], 'allow' => true, 'roles' => ['invoice_cancel']],
                        ['actions' => ['insurance'], 'allow' => true, 'roles' => ['invoice_insurance']],
                        ['actions' => ['enumeration'], 'allow' => true, 'roles' => ['invoice_enumeration']],
                        ['actions' => ['debt'], 'allow' => true, 'roles' => ['invoice_debt']],
                        [
                            'actions' => ['get-invoice', 'get-invoice-payment-details'],
                            'allow' => true,
                            'roles' => ['invoice_details']
                        ],
                        ['actions' => ['insurance-invoice'], 'allow' => true, 'roles' => ['view_insurance_invoices']],
                        ['actions' => ['enumeration-invoice'], 'allow' => true, 'roles' => ['view_enumeration_invoices']],
                        [
                            'actions' => ['details'],
                            'allow' => true,
                            'roles' => ['view_enumeration_invoices', 'view_insurance_invoices']
                        ],
                        [
                            'actions' => ['pay-insurance-invoice'],
                            'allow' => true,
                            'roles' => ['pay_enumeration_invoice', 'pay_insurance_invoice']
                        ],
                        ['actions' => ['delete'], 'allow' => true, 'roles' => ['invoice_delete']],
                    ],
                ],
            ]
        );
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Invoice
    {
        if (($model = Invoice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return array|string[]
     */
    public function actionAjaxCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = $this->request->post();
            $patient = Patient::find()->where(['id' => $post['patient_id']])->one();
            if (!$patient) {
                throw new \Exception('Пациент не найден');
            }

            $priceListItems = PriceListItem::find()
                ->where(['id' => array_column($post['invoice_services'], 'id')])
                ->indexBy('id')
                ->all();

            $invoice = new Invoice();
            $invoice->patient_id = $patient->id;
            $invoice->discount = $patient->discount;
            $invoice->invoice_number = 'n/a';
            $invoice->doctor_id = $post['doctor_id'];
            $invoice->reception_id = $post['reception_id'];
            $invoice->preliminary = $post['preliminary'];
            $invoice->assistant_id = $post['assistant_id'];
            $invoice->type = Invoice::TYPE_NEW;
            $invoice->status = Invoice::STATUS_UNPAID;

            if (!$invoice->save()) {
                return [
                    'status' => 'fail',
                    'message' => implode("<br/>", ArrayHelper::getColumn($invoice->errors, 0, false))
                ];
            }

            $invoice->reception->state = 'admission_finished';
            $invoice->reception->save(false);

            $patient->last_activity = date('Y-m-d H:i:s');
            $patient->save(false);

            $invoiceServices = array_map(static function ($invoiceService) use ($invoice, $patient, $priceListItems) {
                $priceListItem = $priceListItems[$invoiceService['id']] ?? null;
                if (!$priceListItem) {
                    return null;
                }

                $invSer = new InvoiceServices();
                $invSer->invoice_id = $invoice->id;
                $invSer->price_list_item_id = $invoiceService['id'];
                $invSer->teeth = $invoiceService['teeth'];
                $invSer->amount = $invoiceService['amount'];
                $invSer->price = $priceListItem->price;
                $invSer->price_with_discount = $patient->discount > 0 && $priceListItem->checkDiscountApply()
                    ? $priceListItem->price * (100 - $patient->discount) / 100
                    : $priceListItem->price;
                $invSer->title = $priceListItem->name;
                $invSer->teeth_amount = count(explode(',', $invoiceService['teeth']));
                if (!$invSer->save()) {
                    return null;
                }

                return $invSer;
            }, $post['invoice_services']);

            if (in_array(null, $invoiceServices, true)) {
                throw new \Exception('Инвойс сервисы не сохранены');
            }

            $transaction->commit();
            return ['status' => 'success', 'message' => $invoice->id];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public function actionPay(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = $this->request->post();
            $amount = $post['amount'];

            $invoice = Invoice::find()
                ->with('patient')
                ->where(['id' => $post['invoice_id']])
                ->one();

            if (!$invoice) {
                return ['status' => 'fail', 'code' => 'invoice_not_found', 'message' => "Инвойс не найден"];
            }

            $invoiceTotal = $invoice->getInvoiceTotal();
            $invoicePayTotal = $invoice->getInvoicePayTotal() + $amount;

            if ($invoiceTotal < $invoicePayTotal) {
                return [
                    'status' => 'fail',
                    'code' => 'invoice_pay_more',
                    'message' => "Сумма оплаты больше суммы инвойса"
                ];
            }

            $patient = $invoice->patient;
            if (!$patient) {
                return ['status' => 'fail', 'code' => 'patient_not_found', 'message' => "Пациент не найден"];
            }

            $moneyDiff = $patient->getPrepayment() - $amount;
            if ($moneyDiff < 0) {
                return ['status' => 'fail', 'code' => 'not_enough_money', 'message' => $moneyDiff];
            }

            $model = new Transaction();
            $model->patient_id = $invoice->patient_id;
            $model->type = Transaction::TYPE_PAY;
            $model->payment_method = '-';
            $model->invoice_id = $post['invoice_id'];
            $model->invoice_number = $invoice->invoice_number;
            $model->amount = $amount;
            $model->user_id = Yii::$app->user->identity->id;
            if (!$model->save()) {
                return [
                    'status' => 'fail',
                    'code' => 'validation_errors',
                    'message' => print_r($model->errors, true)
                ];
            }

            if ($invoice->type === Invoice::TYPE_NEW) {
                if ($invoicePayTotal == $invoiceTotal) {
                    $invoice->type = Invoice::TYPE_CLOSED;
                    $invoice->status = Invoice::STATUS_PAID;
                    $invoice->closed_at = date('Y-m-d H:i:s');
                } else {
                    $invoice->type = Invoice::TYPE_DEBT;
                    $invoice->status = Invoice::STATUS_UNPAID;
                }
            } elseif ($invoicePayTotal == $invoiceTotal) {
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->closed_at = date('Y-m-d H:i:s');
            }

            if (!$invoice->save()) {
                return [
                    'status' => 'fail',
                    'code' => 'validation_errors',
                    'message' => print_r($invoice->errors, true)
                ];
            }

            //calculate salary for doctor
            if (!empty($invoice->doctor_id)) {
                $calcSalary = $invoice->calculateEmployeeSalary($invoice->doctor_id, $amount);
                if ($calcSalary['status'] === 'fail') {
                    throw new \Exception ($calcSalary['message']);
                }
            }

            //calculate salary for assistant
            if (!empty($invoice->assistant_id)) {
                $calcSalary = $invoice->calculateEmployeeSalary($invoice->assistant_id, $amount);
                if ($calcSalary['status'] === 'fail') {
                    throw new \Exception ($calcSalary['message']);
                }
            }

            // calculate salary for technician
            $technician = User::find()->where(
                [
                    'role' => UserRole::ROLE_TECHNICIAN,
                    'status' => User::STATUS_ACTIVE,
                    'work_status' => User::WORK_STATUS_AVAILABLE
                ]
            )->one();

            if (!empty($technician)) {
                $calcSalary = $invoice->calculateTechnicianSalary($technician->id);
                if ($calcSalary['status'] === 'fail') {
                    throw new \Exception ($calcSalary['message']);
                }
            }

            //calculate report
            $calcReport = $invoice->calculateReport($amount);
            if ($calcReport['status'] === 'fail') {
                throw new \Exception ($calcReport['message']);
            }

            $patient->autoAssigneeDiscount();

            $transaction->commit();
            return [
                'status' => 'success',
                'message' => $model->id
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    /**
     * @param int $id
     * @return array|ActiveRecord|null
     */
    public function actionGetInvoice(int $id)
    {
        $invoice = Invoice::find()
            ->where(['invoice.id' => $id])
            ->joinWith('invoiceServices')
            ->joinWith([
                'doctor' => function ($query) {
                    $query->select(['id', 'firstname', 'lastname']);
                }
            ])
            ->joinWith('invoiceServices')
            ->with([
                'payments' => function ($query) {
                    $query->select(['id', 'invoice_id', 'amount', 'user_id', 'created_at'])
                        ->with([
                            'user' => function ($query) {
                                $query->select(['id', 'firstname', 'lastname']);
                            }
                        ]);
                }
            ])
            ->asArray()
            ->one();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $invoice;
    }

    public function actionGetInvoicePaymentDetails($id)
    {
        $invoice = Invoice::find()
            ->select(['id'])
            ->where(['id' => $id])
            ->with([
                'payments' => function ($query) {
                    $query->select(['id', 'invoice_id', 'amount']);
                }
            ])
            ->with([
                'invoiceServices' => function ($query) {
                    $query->select(['id', 'invoice_id', 'price_with_discount', 'amount', 'teeth_amount']);
                }
            ])
            ->asArray()
            ->one();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $invoice;
    }

    /**
     * @return string
     */
    public function actionInsuranceInvoice(): string
    {
        $this->layout = 'main.php';
        $invoices = Invoice::find()
            ->notPreliminary()
            ->andWhere(['type' => Invoice::TYPE_INSURANCE])
            ->with('patient')
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('insurance-invoice', [
            'invoices' => $invoices
        ]);
    }

    public function actionEnumerationInvoice(): string
    {
        $this->layout = 'main.php';
        $invoices = Invoice::find()
            ->notPreliminary()
            ->andWhere(['type' => Invoice::TYPE_ENUMERATION])
            ->with('patient')
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('enumeration-invoice', [
            'invoices' => $invoices
        ]);
    }


    /**
     * @param int $id
     * @return array|string[]
     */
    public function actionDetails(int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $invoice = Invoice::find()
            ->notPreliminary()
            ->where(['id' => $id])
            ->andWhere(
                ['type' => [Invoice::TYPE_INSURANCE, Invoice::TYPE_ENUMERATION], 'status' => Invoice::STATUS_UNPAID]
            )
            ->with('patient', 'reception', 'payments')
            ->one();

        if (isset($invoice)) {
            $paymentsSum = 0;
            foreach ($invoice->payments as $payment) {
                $paymentsSum += $payment->amount;
            }
            return [
                'status' => 'success',
                'content' => $this->renderAjax('_invoice-content-modal.php', [
                    'invoice' => $invoice,
                    'paymentsSum' => $paymentsSum
                ])
            ];
        }

        return [
            'status' => 'fail',
            'message' => 'Инвойс не найден'
        ];
    }

    public function actionPayInsuranceInvoice(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $post = $this->request->post();
            $amount = $post['amount'];

            $invoice = Invoice::find()
                ->with('patient')
                ->where(['id' => $post['invoice_id']])
                ->one();

            if (!$invoice) {
                return ['status' => 'fail', 'code' => 'invoice_not_found', 'message' => "Инвойс не найден"];
            }

            if (!($invoice->type === Invoice::TYPE_INSURANCE || $invoice->type === Invoice::TYPE_ENUMERATION) && $invoice->status === Invoice::STATUS_UNPAID) {
                return [
                    'status' => 'fail',
                    'code' => 'invoice_type_error',
                    'message' => 'Инвойс не может быть оплачен'
                ];
            }

            $invoiceTotal = $invoice->getInvoiceTotal();
            $invoicePayTotal = $invoice->getInvoicePayTotal() + $amount;

            if ($invoiceTotal < $invoicePayTotal) {
                return [
                    'status' => 'fail',
                    'code' => 'invoice_pay_more',
                    'message' => "Сумма оплаты больше суммы инвойса"
                ];
            }

            $patient = $invoice->patient;
            if (!$patient) {
                return ['status' => 'fail', 'code' => 'patient_not_found', 'message' => "Пациент не найден"];
            }

            $model = new Transaction();
            $model->patient_id = $invoice->patient_id;
            $model->type = Transaction::TYPE_ADD_MONEY;
            $model->payment_method = Transaction::PAYMENT_METHOD_TRANSFER;
            $model->amount = $amount;
            $model->user_id = Yii::$app->user->identity->id;
            if (!$model->save()) {
                return [
                    'status' => 'fail',
                    'code' => 'validation_errors',
                    'message' => print_r($model->errors, true)
                ];
            }

            $model = new Transaction();
            $model->patient_id = $invoice->patient_id;
            $model->type = Transaction::TYPE_PAY;
            $model->payment_method = '-';
            $model->invoice_id = $post['invoice_id'];
            $model->invoice_number = $invoice->invoice_number;
            $model->amount = $amount;
            $model->user_id = Yii::$app->user->identity->id;
            if (!$model->save()) {
                return [
                    'status' => 'fail',
                    'code' => 'validation_errors',
                    'message' => print_r($model->errors, true)
                ];
            }

            if ($invoicePayTotal == $invoiceTotal) {
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->closed_at = date('Y-m-d H:i:s');
            }

            if (!$invoice->save()) {
                return [
                    'status' => 'fail',
                    'code' => 'validation_errors',
                    'message' => print_r($invoice->errors, true)
                ];
            }

            //calculate salary for doctor
            if (!empty($invoice->doctor_id)) {
                $calcSalary = $invoice->calculateEmployeeSalary($invoice->doctor_id, $amount);
                if ($calcSalary['status'] === 'fail') {
                    throw new \Exception ($calcSalary['message']);
                }
            }

            //calculate salary for assistant
            if (!empty($invoice->assistant_id)) {
                $calcSalary = $invoice->calculateEmployeeSalary($invoice->assistant_id, $amount);
                if ($calcSalary['status'] === 'fail') {
                    throw new \Exception ($calcSalary['message']);
                }
            }

            // calculate salary for technician
            $technician = User::find()->where(
                [
                    'role' => UserRole::ROLE_TECHNICIAN,
                    'status' => User::STATUS_ACTIVE,
                    'work_status' => User::WORK_STATUS_AVAILABLE
                ]
            )->one();

            if (!empty($technician)) {
                $calcSalary = $invoice->calculateTechnicianSalary($technician->id);
                if ($calcSalary['status'] === 'fail') {
                    throw new \Exception ($calcSalary['message']);
                }
            }

            //calculate report
            $calcReport = $invoice->calculateReport($amount);
            if ($calcReport['status'] === 'fail') {
                throw new \Exception ($calcReport['message']);
            }

            $patient->autoAssigneeDiscount();

            $transaction->commit();
            return [
                'status' => 'success',
                'message' => $model->id
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    /**
     * @return array|string[]
     */
    public function actionCancel(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = $this->request->post();
        $invoice = Invoice::findOne($post['invoice_id']);

        if ($invoice === null) {
            $response = [
                'status' => 'fail',
                'code' => 'not_found',
                'message' => 'Инвойс не найден'
            ];
        } elseif ($invoice->type !== Invoice::TYPE_NEW) {
            $response = [
                'status' => 'fail',
                'code' => 'cancel_only_new',
                'message' => 'Отменить можно только новый инвойс'
            ];
        } else {
            $invoice->type = Invoice::TYPE_CANCELLED;
            $invoice->status = Invoice::STATUS_PAID;
            if (!$invoice->save()) {
                $response = [
                    'status' => 'fail',
                    'code' => 'validation_errors',
                    'message' => print_r($invoice->errors, true)
                ];
            } else {
                $response = [
                    'status' => 'success',
                    'message' => $invoice->id
                ];
            }
        }

        return $response;
    }

    public function actionInsurance(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $invoiceId = $this->request->post('invoice_id');
        $invoice = Invoice::findOne($invoiceId);

        if ($invoice === null) {
            return [
                'status' => 'fail',
                'code' => 'not_found',
                'message' => 'Инвойс не найден'
            ];
        }

        return $this->updateInvoiceType($invoice, Invoice::TYPE_INSURANCE);
    }

    public function actionEnumeration(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $invoiceId = $this->request->post('invoice_id');
        $invoice = Invoice::findOne($invoiceId);

        if ($invoice === null) {
            return [
                'status' => 'fail',
                'code' => 'not_found',
                'message' => 'Инвойс не найден'
            ];
        }

        return $this->updateInvoiceType($invoice, Invoice::TYPE_ENUMERATION);
    }

    public function actionDebt(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $invoiceId = $this->request->post('invoice_id');
        $invoice = Invoice::findOne($invoiceId);

        if ($invoice === null) {
            return [
                'status' => 'fail',
                'code' => 'not_found',
                'message' => 'Инвойс не найден'
            ];
        }

        return $this->updateInvoiceType($invoice, Invoice::TYPE_DEBT);
    }

    /**
     * @param int $id
     * @return array|string[]
     */
    public function actionDelete(int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $invoice = Invoice::findOne($id);
            if ($invoice) {
                if (($invoice->type === Invoice::TYPE_DEBT || $invoice->type === Invoice::TYPE_INSURANCE) && $invoice->status === Invoice::STATUS_UNPAID) {
                    Report::deleteAll(['invoice_id' => $invoice->id]);
                    EmployeeSalary::deleteAll(['invoice_id' => $invoice->id]);
                    Transaction::deleteAll(['invoice_id' => $invoice->id]);

                    $deleteLog = new DeleteLog();
                    $deleteLog->user_id = Yii::$app->user->identity->id;
                    $deleteLog->object_type = 'invoice';
                    $deleteLog->object_id = $invoice->id;
                    $deleteLog->object_data = json_encode($invoice->toArray());
                    $deleteLog->name = Yii::$app->user->identity->firstname . ' ' . Yii::$app->user->identity->lastname;
                    if (!$deleteLog->save()) {
                        throw new \Exception('Ошибка сохранения лога удаления');
                    }
                    $invoice->type = Invoice::TYPE_CANCELLED;
                    if (!$invoice->save()) {
                        throw new \Exception('Ошибка сохранения инвойса');
                    }

                    $transaction->commit();
                    return ['status' => 'success', 'message' => 'Инвойс удален'];
                } else {
                    throw new \Exception('Инвойс не может быть удален');
                }
            } else {
                throw new \Exception('Инвойс не найден');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    /**
     * @param $invoice
     * @param $type
     * @return array|string[]
     */
    private function updateInvoiceType($invoice, $type): array
    {
        if ($invoice->type === Invoice::TYPE_NEW) {
            $invoice->type = $type;
            $invoice->status = Invoice::STATUS_UNPAID;

            if ($invoice->save()) {
                return [
                    'status' => 'success',
                    'message' => $invoice->id
                ];
            }

            return [
                'status' => 'fail',
                'code' => 'validation_errors',
                'message' => print_r($invoice->errors, true)
            ];
        }

        return [
            'status' => 'fail',
            'code' => 'already_' . strtolower($type),
            'message' => 'Инвойс уже является ' . strtolower($type)
        ];
    }
}
