<?php

namespace backend\controllers;

use common\models\TelegramNotificationSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * TelegramNotificationController implements the CRUD actions for TelegramNotification model.
 */
class TelegramNotificationController extends Controller
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
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['telegram_notification_index']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TelegramNotification models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $notifications = TelegramNotificationSearch::find()->all();

        return $this->render('index', [
            'notifications' => $notifications,
        ]);
    }
}
