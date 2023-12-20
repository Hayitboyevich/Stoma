<?php

namespace common\models\telegram;

use common\models\Category;
use common\models\Subscriber;
use common\models\Telegram;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TgInlineQueryScenario extends Model
{

    public $input;
    function __construct($input)
    {
        parent::__construct();
        $this->input = $input;
    }

    public function run(){
        $telegram = new Telegram($this->input);

        $telegram->answerInlineQuery([]);
    }
}
