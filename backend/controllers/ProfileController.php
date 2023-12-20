<?php

namespace backend\controllers;

use common\models\constants\UserRole;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class ProfileController extends Controller
{
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
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['@']],
                        ['actions' => ['change-assistant'], 'allow' => true, 'roles' => ['doctor', 'user_ajax_update']]
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
     * @return string
     */
    public function actionIndex(): string
    {
        $startDate = Yii::$app->request->get('start_date', date('Y-m-d'));
        $endDate = Yii::$app->request->get('end_date', date('Y-m-d'));
        $model = User::findOne(Yii::$app->user->id);
        $earnings = $model->getEarningsByDate($startDate, $endDate)->all();

        $assistants = [];
        if (Yii::$app->user->can('doctor')) {
            $assistants = User::find()
                ->where(['role' => UserRole::ROLE_ASSISTANT, 'status' => User::STATUS_ACTIVE])
                ->all();
        }

        return $this->render('index', [
            'model' => $model,
            'data' => ['start_date' => $startDate, 'end_date' => $endDate],
            'assistants' => $assistants,
            'earnings' => $earnings,
        ]);
    }

    /**
     * @return array|string[]
     */
    public function actionChangeAssistant(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $model = User::findOne($post['doctorId']);
        if (isset($model, $post['assistantId'])) {
            $model->assistant_id = $post['assistantId'] != 0 ? $post['assistantId'] : null;
            if ($model->save()) {
                $output = ['status' => 'success'];
            } else {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'Doctor or Assistant is not found'];
        }

        return $output;
    }
}
