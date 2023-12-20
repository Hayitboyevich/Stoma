<?php

namespace backend\controllers;

use common\models\AppointmentRequest;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AppointmentRequestController implements the CRUD actions for AppointmentRequest model.
 */
class AppointmentRequestController extends Controller
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
                        ['actions' => ['handle'], 'allow' => true, 'roles' => ['appointment_request_handle']],
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['appointment_request_index']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all AppointmentRequest models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();

        $appointmentRequests = AppointmentRequest::find()
            ->orderBy(['id' => SORT_DESC]);

        $total_rows = $appointmentRequests->count();
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

        $data['appointment_requests'] = $appointmentRequests
            ->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();

        $data['last_row_number'] = $data['offset'] + $data['per_page'] * $data['page'];

        if ($data['last_row_number'] > $data['total_rows']) {
            $data['last_row_number'] = $data['total_rows'];
        }

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || array_key_exists('per_page', $get);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    /**
     * @return array|string[]
     */
    public function actionHandle(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $model = AppointmentRequest::findOne($post['id']);
        if ($model) {
            if (isset($post['status'])) {
                $model->status = $post['status'];
                $model->operator_id = Yii::$app->user->identity->id;
                if ($model->save()) {
                    $output = ['status' => 'success', 'message' => $model->id];
                } else {
                    $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
                }

                return $output;
            }

            return [
                'status' => 'fail',
                'message' => 'Неверный статус'
            ];
        }

        return [
            'status' => 'fail',
            'message' => 'Запись не найдена'
        ];
    }

    /**
     * Finds the AppointmentRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return AppointmentRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): AppointmentRequest
    {
        if (($model = AppointmentRequest::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
