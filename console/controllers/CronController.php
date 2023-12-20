<?php

namespace console\controllers;


use common\models\Reception;
use common\models\SmsGateway;
use common\models\Telegram;
use common\models\TelegramNotification;
use yii\console\Controller;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class CronController extends Controller
{
    function actionSendTelegramNotifications()
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
