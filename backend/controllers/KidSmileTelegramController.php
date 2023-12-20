<?php

namespace backend\controllers;

use common\models\AppointmentRequest;
use common\models\Step;
use common\models\Telegram;
use common\models\telegram\TgCommon;
use common\models\telegram\TgKeyboard;
use Yii;

class KidSmileTelegramController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id === 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $data = file_get_contents('php://input');
        $input = json_decode($data);

        $input->type = TgCommon::getMessageType($input);
        $input->chat_id = TgCommon::getChatID($input);

        $text = '';
        if ($input->type === 'text') {
            $text = $input->message->text;
        }

        if ($text === "/start") {
            $step = Step::findOne(['chat_id' => $input->chat_id]);
            if (!isset($step)) {
                $step = new Step();
                $step->chat_id = $input->chat_id;
            }
            $step->step_1 = 0;
            $step->step_2 = 0;
            $step->save();

            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            $telegram->sendMessage([
                'text' => '🇷🇺 | Добро пожаловать в чат-бот детской стоматологии Kidsmile!

Здесь вы можете узнать стоимость наших услуг и записать своего ребёнка на приём.

🇺🇿 | Kidsmile bolalar stomatologiyasi chat-botiga xush kelibsiz!

Bu yerda siz bizning xizmatlarimiz narxini bilib olishingiz va farzandingiz uchun qabulga yozilishingiz mumkin.'
            ]);
            $buttons = [
                ['text' => '🇷🇺 Русский язык'],
                ['text' => '🇺🇿 O\'zbek tili']
            ];
            $telegram->sendMessageWithButtons(
                [
                    'text' => '🇷🇺 | Выберите удобный для Вас язык:' . PHP_EOL . PHP_EOL . '🇺🇿 | O\'zingizga qulay tilni tanlang:',
                    'buttons' => TgKeyboard::formatKeyboard($buttons, 2)
                ]
            );
        }

        $step = Step::findOne(['chat_id' => $input->chat_id]);
        $step_1 = 0;
        $step_2 = 0;
        if (isset($step)) {
            $step_1 = (int)$step->step_1;
            $step_2 = (int)$step->step_2;
        } else {
            $step = new Step();
            $step->chat_id = $input->chat_id;
            $step->step_1 = 0;
            $step->step_2 = 0;
            $step->save();
        }

        if ($step_2 == 0 && $text != "/start") {
            if ($text == "🇷🇺 Русский язык" || $text == "🇺🇿 O'zbek tili") {
                $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
                AppointmentRequest::deleteAll(['chat_id' => $input->chat_id, 'status' => 3]);

                $step = Step::findOne(['chat_id' => $input->chat_id]);
                $step->step_1 = $text == "🇷🇺 Русский язык" ? 2 : 1;
                $step->step_2 = 1;
                $step->save();

                if ($text == "🇷🇺 Русский язык") {
                    $sendText = "Фамилия родителя или взрослого сопровождающего:";
                } else {
                    $sendText = "Ota-onasining yoki katta yoshli hamrohining familiyasi:";
                }
                $telegram->sendMessage(['text' => $sendText, 'remove_keyboard' => true]);
                die();
            }
        }

        if ($step_2 == 1 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text)) {
                $appointmentRequest = new AppointmentRequest();
                $appointmentRequest->chat_id = $input->chat_id;
                $appointmentRequest->status = 3;
                $appointmentRequest->parent_last_name = $text;
                $appointmentRequest->source = AppointmentRequest::SOURCE_KID_SMILE_BOT;
                $appointmentRequest->save();

                $step = Step::findOne(['chat_id' => $input->chat_id]);
                $step->step_2 = 2;
                $step->save();

                if ($step_1 == 2) {
                    $sendText = "Имя родителя или взрослого сопровождающего:";
                } else {
                    $sendText = "Ota-onasining yoki katta yoshli hamrohining ismi:";
                }

                $telegram->sendMessage(['text' => $sendText]);
                die();
            }
            $telegram->deleteMessage($input);
        }

        if ($step_2 == 2 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text)) {
                $appointmentRequest = AppointmentRequest::findOne(['chat_id' => $input->chat_id, 'status' => 3]);
                if (isset($appointmentRequest)) {
                    $appointmentRequest->parent_first_name = $text;
                    $appointmentRequest->save();
                }

                $step = Step::findOne(['chat_id' => $input->chat_id]);
                $step->step_2 = 3;
                $step->save();

                if ($step_1 == 2) {
                    $sendText = "Номер телефон родителя или взрослого сопровождающего:";
                    $buttons = [
                        ['text' => 'Отправить номер телефона 📲', 'request_contact' => true]
                    ];
                } else {
                    $sendText = "Ota-onasining yoki katta yoshli hamrohining telefon raqami:";
                    $buttons = [
                        ['text' => 'Telefon raqam yuborish 📲', 'request_contact' => true]
                    ];
                }

                $telegram->sendMessageWithButtons(
                    ['text' => $sendText, 'buttons' => TgKeyboard::formatKeyboard($buttons)]
                );
                die();
            }
            $telegram->deleteMessage($input);
        }

        if ($step_2 == 3 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text) || isset($input->message->contact)) {
                $appointmentRequest = AppointmentRequest::findOne(['chat_id' => $input->chat_id, 'status' => 3]);
                if (isset($appointmentRequest)) {
                    $appointmentRequest->phone = $input->message->contact->phone_number ?? $text;
                    $appointmentRequest->save();
                }

                $step = Step::findOne(['chat_id' => $input->chat_id]);
                $step->step_2 = 4;
                $step->save();

                if ($step_1 == 2) {
                    $sendText = "Выберите нужный раздел ⤵️";

                    $buttons = [
                        ['text' => '✍️ Записаться на приём'],
                        ['text' => '📋 Узнать стоимость услуг']
                    ];
                } else {
                    $sendText = "Kerakli bo'limni tanlang ⤵️";

                    $buttons = [
                        ['text' => '✍️ Qabulga yozilish'],
                        ['text' => '📋 Xizmatlar narxini bilish']
                    ];
                }

                $telegram->sendMessageWithButtons(
                    ['text' => $sendText, 'buttons' => TgKeyboard::formatKeyboard($buttons, 2)]
                );
                die();
            }
            $telegram->deleteMessage($input);
        }

        if ($step_2 == 4 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text)) {
                if ($text == "✍️ Записаться на приём" || $text == "✍️ Qabulga yozilish") {
                    $step = Step::findOne(['chat_id' => $input->chat_id]);
                    $step->step_2 = 5;
                    $step->save();

                    if ($step_1 == 2) {
                        $sendText = 'Фамилия ребёнка:';
                    } else {
                        $sendText = 'Bolaning familiyasi:';
                    }
                    $telegram->sendMessage(['text' => $sendText, 'remove_keyboard' => true]);
                } elseif ($text == "📋 Узнать стоимость услуг" || $text == "📋 Xizmatlar narxini bilish") {
                    $telegram->sendDocument(
                        ['document' => 'https://devadmin.stomaservicecrm.ssd.uz/uploads/Kidsmile_price.pdf']
                    );
                }
                die();
            }
            $telegram->deleteMessage($input);
        }

        if ($step_2 == 5 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text)) {
                $appointmentRequest = AppointmentRequest::findOne(['chat_id' => $input->chat_id, 'status' => 3]);
                if (isset($appointmentRequest)) {
                    $appointmentRequest->last_name = $text;
                    $appointmentRequest->save();
                }

                $step = Step::findOne(['chat_id' => $input->chat_id]);
                $step->step_2 = 6;
                $step->save();

                if ($step_1 == 2) {
                    $sendText = 'Имя ребёнка:';
                } else {
                    $sendText = 'Bolaning ismi:';
                }
                $telegram->sendMessage(['text' => $sendText]);
                die();
            }
            $telegram->deleteMessage($input);
        }

        if ($step_2 == 6 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text)) {
                $appointmentRequest = AppointmentRequest::findOne(['chat_id' => $input->chat_id, 'status' => 3]);
                if (isset($appointmentRequest)) {
                    $appointmentRequest->first_name = $text;
                    $appointmentRequest->save();
                }

                $step = Step::findOne(['chat_id' => $input->chat_id]);
                $step->step_2 = 7;
                $step->save();

                if ($step_1 == 2) {
                    $sendText = 'Дата рождения ребёнка:' . PHP_EOL . '<i>Пример: 2010-04-17</i>';
                } else {
                    $sendText = 'Bolaning tugʻilgan sanasi: ' . PHP_EOL . '<i>Namuna: 2010-04-17</i>';
                }
                $telegram->sendMessage(['text' => $sendText, 'parse_mode' => 'html']);
                die();
            }
            $telegram->deleteMessage($input);
        }

        if ($step_2 == 7 && $text != "/start") {
            $telegram = new Telegram($input, Yii::$app->params['kid_smile_telegram_bot']);
            if (isset($input->message->text)) {
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $text)) {
                    $appointmentRequest = AppointmentRequest::findOne(['chat_id' => $input->chat_id, 'status' => 3]);
                    if (isset($appointmentRequest)) {
                        $appointmentRequest->dob = $text;
                        $appointmentRequest->status = 0;
                        $appointmentRequest->save();
                    }

                    $step = Step::findOne(['chat_id' => $input->chat_id]);
                    $step->step_2 = 8;
                    $step->save();

                    if ($step_1 == 2) {
                        $sendText = "Заявка номер #{$appointmentRequest->id} принята, в ближайшее время с вами свяжется оператор.";
                    } else {
                        $sendText = "Ariza raqami #{$appointmentRequest->id} qabul qilindi, operator tez orada siz bilan bog'lanadi.";
                    }
                    $telegram->sendMessage(['text' => $sendText]);
                } else {
                    if ($step_1 == 2) {
                        $errorText = '<b>Дата рождения ребёнка введена неверно❗️</b>' . PHP_EOL . '<i>Пример: 2010-04-14</i>';
                    } else {
                        $errorText = '<b>Bolaning tug\'ilgan sanasi notog\'ri kiritildi❗️</b>' . PHP_EOL . '<i>Namuna: 2010-04-14</i>';
                    }

                    $telegram->sendMessage(['text' => $errorText, 'parse_mode' => 'html']);
                }
                die();
            }
            $telegram->deleteMessage($input);
        }
    }
}
