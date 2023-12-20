<?php

namespace backend\controllers;

use common\models\Invoice;
use common\models\PatientExamination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class PrintController extends Controller
{
    public function behaviors(): array
    {
        $this->layout = 'blank';
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
                        ['actions' => ['examination'], 'allow' => true, 'roles' => ['@']],
                        ['actions' => ['invoice'], 'allow' => true, 'roles' => ['@']],
                    ],
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        return parent::beforeAction($action);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionExamination($id): string
    {
        $model = PatientExamination::findOne($id);
        return $this->render('examination', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionInvoice($id): string
    {
        $model = Invoice::find()
            ->where(['id' => $id])
            ->with(['patient', 'doctor', 'invoiceServices.priceListItem', 'payments'])
            ->one();

        return $this->render('invoice', ['model' => $model]);
    }
}
