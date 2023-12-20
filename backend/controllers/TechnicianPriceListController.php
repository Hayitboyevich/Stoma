<?php

namespace backend\controllers;

use common\models\TechnicianPriceList;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TechnicianPriceListController implements the CRUD actions for TechnicianPriceList model.
 */
class TechnicianPriceListController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
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
                        [
                            'actions' => [
                                'index',
                                'ajax-create',
                                'ajax-delete',
                                'ajax-update',
                                'ajax-get-technician-price-list'
                            ],
                            'allow' => true,
                            'roles' => ['technician_price_list_manage']
                        ]
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TechnicianPriceList models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $model = TechnicianPriceList::find()
            ->where(['status' => TechnicianPriceList::STATUS_ACTIVE])
            ->all();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionAjaxCreate(): array
    {
        $post = Yii::$app->request->post();
        $technicianPriceList = new TechnicianPriceList();
        $technicianPriceList->name = $post['name'];
        $technicianPriceList->price = $post['price'];
        $technicianPriceList->status = TechnicianPriceList::STATUS_ACTIVE;
        if ($technicianPriceList->save()) {
            $output = ['status' => 'success', 'message' => $technicianPriceList->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($technicianPriceList->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionAjaxUpdate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $technicianPriceList = TechnicianPriceList::findOne($post['id']);
        if (!$technicianPriceList) {
            return ['status' => 'fail', 'message' => 'Такой записи не существует'];
        }
        $technicianPriceList->name = $post['name'];
        $technicianPriceList->price = $post['price'];
        if ($technicianPriceList->save()) {
            $output = ['status' => 'success', 'message' => $technicianPriceList->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($technicianPriceList->errors, true)];
        }

        return $output;
    }

    public function actionAjaxGetTechnicianPriceList($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $technicianPriceList = TechnicianPriceList::findOne($id);
        if (!$technicianPriceList) {
            return ['status' => 'fail', 'message' => 'Такой записи не существует'];
        }
        return ['status' => 'success', 'data' => $technicianPriceList];
    }

    public function actionAjaxDelete(): int
    {
        return TechnicianPriceList::updateAll(
            ['status' => TechnicianPriceList::STATUS_INACTIVE],
            ['IN', 'id', Yii::$app->request->post('ids')]
        );
    }

    /**
     * Finds the TechnicianPriceList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TechnicianPriceList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): TechnicianPriceList
    {
        if (($model = TechnicianPriceList::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
