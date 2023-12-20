<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ReportController extends Controller
{
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
                        ['actions' => ['*'], 'allow' => true, 'roles' => ['admin']],
                    ],
                ],
            ]
        );
    }

    public function actionDynamics()
    {
        return $this->render('dynamics', ['action' => $this->action->id]);
    }

    public function actionDaily()
    {
        $params['action'] = $this->action->id;
        $params['date'] = array_key_exists('date', $this->request->get()) ? $this->request->get('date') : date('Y-m-d');
        return $this->render('daily', ['params' => $params]);
    }

    public function actionDebitCredit()
    {
        $params['action'] = $this->action->id;
        return $this->render('debit-credit', ['params' => $params]);
    }

    public function actionInvoices()
    {
        $params['action'] = $this->action->id;
        return $this->render('invoices', ['params' => $params]);
    }

    public function actionSalary()
    {
        $params['action'] = $this->action->id;
        return $this->render('salary', ['params' => $params]);
    }

    public function actionPeriodReport()
    {
        $params['action'] = $this->action->id;
        return $this->render('period-report', ['params' => $params]);
    }
}
