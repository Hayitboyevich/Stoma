<?php

namespace common\models;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
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
class SmsGateway extends Model
{

    public $service_id;
    public $secret_key;
    public $endpoint;
    public $id;
    public $recipient;
    public $text;
    public $originator;
    const TEMPLATES = [
        'remind_sms' => "Уважаемый/-ая {fullname}, Вы записаны на прием {time} {date} к доктору {doctor} \nС заботой, Stomaservice",
        'money_credited' => "Уважаемый/-ая {fullname}, ваш баланс пополнен на {amount} сум. С заботой, Stomaservice",
        'money_withdrawn' => "Уважаемый/-ая {fullname}, с вашего баланса списано {amount} сум. С заботой, Stomaservice",
        'invoice_paid' => "Уважаемый/-ая {fullname}, ваш счет №{invoice_number} на {invoice_price} сум оплачен на {paid_amount} сум. С заботой, Stomaservice",
        'have_debt' => "Уважаемый/-ая {fullname}, у вас имеется долг, просим своевременно оплатить счета. С заботой, Stomaservice",
        'reset_code' => "Ваш код для восстановления в системе: {code}, Команда Stomaservice",
    ];

    function __construct($config = [])
    {
        parent::__construct();
        if (empty($config)) {
            $config = Yii::$app->params['sms'];
        }

        $this->service_id = $config['service_id'];
        $this->secret_key = $config['secret_key'];
        $this->endpoint = $config['endpoint'];
        $this->originator = $config['originator'];
    }

    public function sendSms()
    {
        $phone = User::getOnlyNumbers($this->recipient);
        $phone = strlen($phone) == 9 ? "998{$phone}" : $phone;
        $smsNotification = new SmsNotification();
        $smsNotification->message = $this->text;
        $smsNotification->phone = $phone;
        $smsNotification->save();
        $timestamp = time();
        $hash = sha1($this->secret_key . $timestamp);
        $auth = $this->service_id . "-" . $hash . "-" . $timestamp;

        $client = new Client();
        $headers = [
            'Auth' => $auth,
            'Content-Type' => 'text/plain',
        ];

        $post_data = [
            'id' => $smsNotification->id,
            'params' => [
                'recipient' => $phone,
                'originator' => $this->originator,
                'partner_id' => $this->service_id,
                'text' => $this->text
            ]
        ];

        $body = json_encode($post_data);
        $request = new Request('POST', $this->endpoint . '/sms/send', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $response = $res->getBody()->getContents();
        $parsed_response = json_decode($response, true);
        $smsNotification->status = empty($parsed_response['error']) ? 'success' : 'fail';
        $smsNotification->response = $response;
        $smsNotification->request_data = json_encode(['headers' => $headers, 'body' => $post_data]);
        $smsNotification->save();
        return ['log_id' => $smsNotification->id, 'response' => $response];
    }

    public static function formatSms($params)
    {
        $template = self::TEMPLATES[$params['template']];
        foreach ($params['data'] as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }

        return $template;
    }
}
