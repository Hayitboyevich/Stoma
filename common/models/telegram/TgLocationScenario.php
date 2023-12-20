<?php

namespace common\models\telegram;

use common\models\Category;
use common\models\ObjectItem;
use common\models\Telegram;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TgLocationScenario extends Model
{

    public $input;
    public $user;
    public $telegram;
    function __construct($input)
    {
        parent::__construct();
        $this->input = $input;
        $this->user = User::findOne(['chat_id' => $this->input->chat_id]);
        $this->telegram = new Telegram($this->input);
    }

    public function run(){

        if(strstr($this->user->last_page,'send_location')){
            $object_item_id = str_replace('send_location_','',$this->user->last_page);
            $object_item = ObjectItem::findOne($object_item_id);
            if($object_item){
                $object_item->lat = $this->input->message->location->latitude;
                $object_item->long = $this->input->message->location->longitude;
                if($object_item->save()){
                    $this->telegram->sendMessage(['text' => 'Локация установлена']);
                }
                else{
                    $this->telegram->sendMessage(['text' => 'Ошибка при сохранение локации']);
                }
            }
        }
        die();
    }
}
