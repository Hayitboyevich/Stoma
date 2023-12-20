<?php

namespace common\models\telegram;

use common\models\Cart;
use common\models\Category;
use common\models\ObjectItem;
use common\models\Order;
use common\models\Product;
use common\models\Subscriber;
use common\models\Telegram;
use common\models\TgCartProductOptionTmp;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\db\StaleObjectException;

/**
 * ContactForm is the model behind the contact form.
 */
class TgCallbackQueryScenario extends Model
{

    public $input;
    public $user;
    function __construct($input)
    {
        parent::__construct();
        $this->input = $input;
        $this->user = User::findOne(['chat_id' => $this->input->chat_id]);
    }

    public function run(){
        $telegram = new Telegram($this->input);

        $text = '';

        if(strstr($this->input->callback_query->data,'object_type_')){

            $telegram->sendMessage(['text' => 'Описание:']);
            $subscriber = User::findOne(['chat_id' => $this->input->chat_id]);
            $object_type_id = str_replace('object_type_','',$this->input->callback_query->data);
            $object_item = new ObjectItem();
            $object_item->object_type_id = $object_type_id;
            $object_item->user_id = $this->user->id;
            $object_item->save();
            $subscriber->last_page = 'set_object_description_'.$object_item->id;
            $subscriber->save(false);
            $telegram->answerCallbackQuery(['text' => 'ok']);
            die();
        }

        $telegram->answerCallbackQuery(['text' => $text]);
        $TgSubscriber = new TgSubscriber($this->input);
        $TgSubscriber->deleteAllInlineMessages();
        $TgPage = new TgPage($this->input);
        $TgPage->showHome();
    }
}
