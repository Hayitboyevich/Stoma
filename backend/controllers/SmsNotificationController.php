<?php

namespace backend\controllers;

use common\models\SmsNotification;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmsNotificationController implements the CRUD actions for SmsNotification model.
 */
class SmsNotificationController extends Controller
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
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['sms_notification_index']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SmsNotification models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();

        $url_start_date = Yii::$app->request->get('start_date');
        $url_end_date = Yii::$app->request->get('end_date');
        $data['start_date'] = !empty($url_start_date) ? $url_start_date : date('Y-m-01');
        $data['end_date'] = !empty($url_end_date) ? $url_end_date : date('Y-m-t');
        $data['status'] = array_key_exists('status', $get) ? $get['status'] : 'all';

        $smsNotifications = SmsNotification::find()
            ->where([
                'and',
                ['>=', 'created_at', $data['start_date'] . ' 00:00:00'],
                ['<=', 'created_at', $data['end_date'] . ' 23:59:59']
            ]);

        if ($data['status'] != 'all') {
            $smsNotifications->andWhere(['status' => $data['status']]);
        }

        $total_rows = $smsNotifications->count();
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

        $data['sms_notifications'] = $smsNotifications->orderBy(['created_at' => SORT_DESC])
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
     * Finds the SmsNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SmsNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): SmsNotification
    {
        if (($model = SmsNotification::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
