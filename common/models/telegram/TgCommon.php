<?php

namespace common\models\telegram;

use common\models\Subscriber;
use common\models\Telegram;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TgCommon extends Model
{
    public $input;

    function __construct($input)
    {
        parent::__construct();
        $this->input = $input;
    }

    public function showChangeLangPage()
    {
        $telegram = new Telegram($this->input);
        $telegram->sendMessageWithInlineButtons(['text' => Yii::t('app', 'Choose lang'), 'buttons' => TgKeyboard::getLanguageList()]);
    }

    public static function logToFile($data)
    {
        file_put_contents(Yii::$app->params['upload_path'] . '/log.txt', print_r($data, true));
    }

    public static function getMessageType($input)
    {
        if (isset($input->callback_query)) {
            $type = 'callback_query';
        } elseif (isset($input->message, $input->message->location)) {
            $type = 'location';
        } elseif (isset($input->inline_query)) {
            $type = 'inline_query';
        } elseif (isset($input->message, $input->message->contact)) {
            $type = 'contact';
        } else {
            $type = 'text';
        }

        return $type;
    }

    public static function getChatID($input)
    {
        switch ($input->type) {
            case 'callback_query':
                $chat_id = $input->callback_query->from->id;
                break;
            case 'inline_query':
                $chat_id = $input->inline_query->from->id;
                break;
            default:
                $chat_id = $input->message->from->id;
        }

        return $chat_id;
    }

    public static function removeEmoji($string)
    {
        $string = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $string);
        $string = preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
    )%xs', '', $string);
        return trim($string);
    }
}
