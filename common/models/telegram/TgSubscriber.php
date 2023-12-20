<?php

namespace common\models\telegram;

use common\models\Patient;
use common\models\User;
use common\models\Telegram;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TgSubscriber extends Model
{

    public $input;
    public $subscriber;

    function __construct($input)
    {
        parent::__construct();
        $this->input = $input;
    }

    public function subscriber()
    {
        $this->subscriber = Patient::findOne(['chat_id' => $this->input->chat_id]);
        if (!$this->subscriber) {
            $this->subscriber = Patient::newSubscriberFromMessage($this->input->message);
        }

        return $this->subscriber;
    }

    public function deleteAllInlineMessages()
    {
        $telegram = new Telegram($this->input);
        $this->subscriber();
        if (!empty($this->subscriber->getInlineMessages())) {
            foreach ($this->subscriber->getInlineMessages() as $inlineMessage) {
                $telegram->deleteMessage(['message_id' => $inlineMessage->message_id]);
                $inlineMessage->delete();
            }
        }
    }
}
