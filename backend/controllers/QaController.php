<?php

namespace backend\controllers;

use common\models\Reception;
use common\models\Telegram;
use common\models\telegram\TgCallbackQueryScenario;
use common\models\telegram\TgCommon;
use common\models\telegram\TgInlineQueryScenario;
use common\models\telegram\TgKeyboard;
use common\models\telegram\TgLocationScenario;
use common\models\telegram\TgSubscriber;
use common\models\telegram\TgTextMessageScenario;
use common\models\TelegramNotification;
use common\models\TmpUser;
use common\models\User;
use Yii;

class QaController extends \yii\web\Controller
{

    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionRunCron()
    {
        $day_before_notifications = Reception::getRecords(
            ['date' => 'DATE_SUB(CURDATE(),INTERVAL -1 DAY)', 'day' => 'day_before']
        );
        if (!empty($day_before_notifications)) {
            foreach ($day_before_notifications as $notification) {
                $reception = Reception::findOne($notification['id']);
                $reception->sendSmsNotification();
                $reception->sendTelegramNotification();
            }
        } else {
            echo "\r\nno day before records";
        }

        $on_the_day_notifications = Reception::getRecords(['date' => 'CURDATE()', 'day' => 'on_the_day']);
        if (!empty($on_the_day_notifications)) {
            foreach ($on_the_day_notifications as $notification) {
                $reception = Reception::findOne($notification['id']);
                $reception->sendSmsNotification();
                $reception->sendTelegramNotification();
            }
        } else {
            echo "\r\nno on the day records";
        }
    }

}
