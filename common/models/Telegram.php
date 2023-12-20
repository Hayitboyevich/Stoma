<?php

namespace common\models;

use common\models\telegram\TgCommon;
use common\models\telegram\TgSubscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $emoji
 * @property string|null $created_at
 */
class Telegram extends Model
{
    public $input;
    public $chat_id;
    public $token;
    public $base_url;

    public function __construct($input = null, $config = null)
    {
        parent::__construct();
        if (empty($config)) {
            $config = Yii::$app->params['telegram_bot'];
        }
        $this->input = $input ?: null;
        $this->chat_id = $input ? $input->chat_id : null;
        $this->token = $config['token'];
        $this->base_url = $config['base_url'];
    }

    public function sendMessage($data)
    {
        if (array_key_exists('remove_keyboard', $data)) {
            $data['reply_markup'] = json_encode(['remove_keyboard' => true]);
        }
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;
        return $this->TelegramAPIRequest('sendMessage', $data);
    }

    public function sendMediaGroup($data)
    {
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;
        $data['media'] = json_encode($data['media']);
        return $this->TelegramAPIRequest('sendMediaGroup', $data);
    }

    public function sendPhoto($data)
    {
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;
        return $this->TelegramAPIRequest('sendPhoto', $data);
    }

    public function sendDocument($data)
    {
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;
        return $this->TelegramAPIRequest('sendDocument', $data);
    }

    public function forwardMessage($data)
    {
        $data['chat_id'] = '-1001484996704';
        $data['from_chat_id'] = '115039079';
        return $this->TelegramAPIRequest('forwardMessage', $data);
    }

    public function answerCallbackQuery($data)
    {
        $data['callback_query_id'] = $this->input->callback_query->id;
        return $this->TelegramAPIRequest('answerCallbackQuery', $data);
    }

    public function answerInlineQuery($data)
    {
        $results = [
            [
                'type' => 'article',
                'title' => 'dddd',
                'description' => 'descr here',
                'id' => uniqid(),
                'thumb_url' => 'https://cottontextile.uz/d/giy_ekxcbo4.jpg',
                'input_message_content' => ['message_text' => 'aaa']
            ],
            [
                'type' => 'article',
                'title' => 'dddd',
                'description' => 'descr here',
                'id' => uniqid(),
                'thumb_url' => 'https://cottontextile.uz/d/giy_ekxcbo4.jpg',
                'input_message_content' => ['message_text' => 'aaa']
            ],
        ];
        $data['inline_query_id'] = $this->input->inline_query->id;
        $data['cache_time'] = 1;
        $data['results'] = json_encode($results);
        $res = $this->TelegramAPIRequest('answerInlineQuery', $data);
        TgCommon::logToFile($res);
        return $res;
    }


    public function deleteMessage($data)
    {
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;
        return $this->TelegramAPIRequest('deleteMessage', $data);
    }

    public function editMessageReplyMarkup($data)
    {
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;
        return $this->TelegramAPIRequest('editMessageReplyMarkup', $data);
    }

    /*
     *
     *  $buttons = [
            ['text' => 'button1'],
            ['text' => 'button2'],
        ];
        $telegram->sendMessageWithButtons(['text' => 'working...','buttons' => TgKeyboard::formatKeyboard($buttons)]);
     * */
    public function sendMessageWithButtons($data)
    {

        $data['reply_markup'] = $data['buttons'];
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;

        return $this->TelegramAPIRequest('sendMessage', $data);
    }

    public function sendMessageWithInlineButtons($data)
    {
        $delete_all_previous_inline_messages = array_key_exists('delete_all_previous_inline_messages', $data) ? $data['delete_all_previous_inline_messages'] : true;

        if ($delete_all_previous_inline_messages) {
            $TgSubscriber = new TgSubscriber($this->input);
            $TgSubscriber->deleteAllInlineMessages();
        }

        $data['reply_markup'] = $data['buttons'];
        $data['chat_id'] = array_key_exists('chat_id', $data) ? $data['chat_id'] : $this->chat_id;

        $resp = $this->TelegramAPIRequest('sendMessage', $data);
        $inlineMessage = new InlineMessage();
        $inlineMessage->chat_id = $this->chat_id;
        $inlineMessage->message_data = $resp;
        $inlineMessage->save();
        return $resp;
    }

    public function TelegramAPIRequest($method, $data)
    {
        sleep(1);
        $url = "{$this->base_url}/bot{$this->token}/{$method}";

        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'query' => $data
            ]);

            return $response->getBody()->getContents();

        } catch (GuzzleException $e) {
            return false;
        }
    }
}
