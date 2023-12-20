<?php

namespace backend\controllers;

use common\models\DeleteLog;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * DeleteLogController implements the CRUD actions for DeleteLog model.
 */
class DeleteLogController extends Controller
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
                    'class' => VerbFilter::class
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['delete_log_index']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DeleteLog models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();

        $totalRows = DeleteLog::find()->count();

        $data['total_rows'] = $totalRows;
        $data['per_page'] = $get['per_page'] ?? 10;
        $data['page'] = $get['page'] ?? 1;
        $data['offset'] = $data['per_page'] * ($data['page'] - 1);

        $data['total_pages'] = ($data['per_page'] >= $data['total_rows']) ? 1 : ceil(
            $data['total_rows'] / $data['per_page']
        );

        $data['delete_logs'] = DeleteLog::find()
            ->orderBy(['deleted_at' => SORT_DESC])
            ->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();

        $data['last_row_number'] = $data['offset'] + $data['per_page'] * $data['page'];
        $data['last_row_number'] = ($data['last_row_number'] > $data['total_rows'])
            ? $data['total_rows']
            : $data['last_row_number'];

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || isset($get['per_page']);

        return $this->render('index', [
            'data' => $data
        ]);
    }
}
