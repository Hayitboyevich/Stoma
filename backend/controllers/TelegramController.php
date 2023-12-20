<?php

namespace backend\controllers;

use common\models\Telegram;
use common\models\telegram\TgCommon;
use common\models\telegram\TgKeyboard;
use common\models\telegram\TgSubscriber;
use common\models\telegram\TgTextMessageScenario;
use common\models\User;
use Yii;
use yii\web\Controller;

class TelegramController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if (in_array($action->id, ['index', 'operator'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex(): void
    {
        $data = file_get_contents('php://input');
        $input = json_decode($data);

        $input->type = TgCommon::getMessageType($input);
        $input->chat_id = TgCommon::getChatID($input);

        $TgSubscriber = new TgSubscriber($input);
        $subscriber = $TgSubscriber->subscriber();
        if (empty($subscriber->lastname)) {
            if ($subscriber->chat_status == 'awaiting_user_enter_lastname') {
                $subscriber->lastname = $input->message->text;
                $subscriber->chat_status = 'user_lastname_saved';
                $subscriber->save();
            } else {
                $telegram = new Telegram($input);
                $telegram->sendMessage(['text' => 'Укажите фамилию:']);
                $subscriber->chat_status = 'awaiting_user_enter_lastname';
                $subscriber->save();
                die();
            }
        }

        if (empty($subscriber->firstname)) {
            if ($subscriber->chat_status == 'awaiting_user_enter_firstname') {
                $subscriber->firstname = $input->message->text;
                $subscriber->chat_status = 'user_firstname_saved';
                $subscriber->save();
            } else {
                $telegram = new Telegram($input);
                $telegram->sendMessage(['text' => 'Укажите имя:']);
                $subscriber->chat_status = 'awaiting_user_enter_firstname';
                $subscriber->save();
                die();
            }
        }

        if (empty($subscriber->phone)) {
            if ($subscriber->chat_status == 'awaiting_user_enter_phone') {
                $subscriber->phone = $input->message->text;
                $subscriber->chat_status = 'user_phone_saved';
                $subscriber->save();
                $telegram = new Telegram($input);
                $buttons = [
                    ['text' => 'Записаться на прием']
                ];
                $telegram->sendMessageWithButtons(
                    [
                        'text' => 'Регистрация прошла успешно, спасибо!',
                        'buttons' => TgKeyboard::formatKeyboard($buttons)
                    ]
                );
            } else {
                $telegram = new Telegram($input);
                $telegram->sendMessage(['text' => 'Укажите номер телефона:']);
                $subscriber->chat_status = 'awaiting_user_enter_phone';
                $subscriber->save();
                die();
            }
        }
        $tgTextMessageScenario = new TgTextMessageScenario($input, Yii::$app->params['telegram_bot']);
        $tgTextMessageScenario->run();
        die();
    }

    public function actionOperator()
    {
        $data = file_get_contents('php://input');
        $input = json_decode($data);

        $input->type = TgCommon::getMessageType($input);
        $input->chat_id = TgCommon::getChatID($input);

        $telegram = new Telegram($input, Yii::$app->params['operator_telegram_bot']);
        if ($input->message->text == '/start') {
            $telegram->sendMessage([
                'chat_id' => $input->chat_id,
                'text' => 'Отправьте логин и пароль в следующим формате:
`login password`',
                'parse_mode' => 'MarkdownV2'
            ]);
        } else {
            list($login, $password) = explode(' ', trim($input->message->text));
            $user = User::findOne(['username' => $login]);
            if ($user && $user->validatePassword($password)) {
                $user->chat_id = $input->chat_id;
                if ($user->save()) {
                    $telegram->sendMessage(
                        ['chat_id' => $input->chat_id, 'text' => "Ваш аккаунт успешно привязан к телеграм, спасибо!"]
                    );
                } else {
                    $telegram->sendMessage(
                        [
                            'chat_id' => $input->chat_id,
                            'text' => print_r($user->errors, true),
                            'parse_mode' => 'MarkdownV2'
                        ]
                    );
                }
            } else {
                $telegram->sendMessage(['chat_id' => $input->chat_id, 'text' => 'Введен неверный логин или пароль!']);
            }
        }
        die();
    }
}
