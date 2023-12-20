<?php

namespace common\models\telegram;

use common\models\AppointmentRequest;
use common\models\Patient;
use common\models\Telegram;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TgTextMessageScenario extends Model
{
    public $input;
    public $patient;
    public $telegram;
    public $username;


    public function __construct($input, $config)
    {
        parent::__construct();
        $this->input = $input;
        $this->patient = Patient::findOne(['chat_id' => $this->input->chat_id]);
        $this->telegram = new Telegram($this->input, $config);
        $this->username = $config['username'];
    }

    public function run(): void
    {
        if ($this->telegram->input->message->text == 'Записаться на прием') {

            if ($this->patient->checkAppointmentAlreadyExist()) {
                $this->telegram->sendMessage(['text' => 'Заявка уже принята, в ближайшее время с вами свяжется оператор.']);
                die();
            }

            $appointmentRequest = new AppointmentRequest();
            $appointmentRequest->patient_id = $this->patient->id;
            $appointmentRequest->last_name = $this->patient->lastname;
            $appointmentRequest->first_name = $this->patient->firstname;
            $appointmentRequest->phone = $this->patient->phone;
            $appointmentRequest->source = $this->username;
            if ($appointmentRequest->save()) {
                $this->telegram->sendMessage(['text' => "Заявка номер #{$appointmentRequest->id} принята, в ближайшее время с вами свяжется оператор."]);
                $appointmentRequest->notifyOperators();
            } else {
                $this->telegram->sendMessage(['text' => print_r($appointmentRequest->errors, true)]);
            }
        }
        die();
    }
}
