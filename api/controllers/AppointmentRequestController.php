<?php

namespace api\controllers;

use api\helpers\ErrorFormatter;
use api\traits\ApiHelper;
use common\models\AppointmentRequest;
use common\models\AppointmentRequestForm;
use common\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class AppointmentRequestController extends Controller
{
    use ApiHelper;

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => function ($username, $password) {
                $user = User::findByUsername($username);
                if ($user && $user->validatePassword($password)) {
                    return $user;
                }
                return null;
            }
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    public function actionCreate(): array
    {
        $appointmentRequestForm = new AppointmentRequestForm();
        $appointmentRequestForm->load(\Yii::$app->request->post(), '');

        if (!$appointmentRequestForm->validate()) {
            return $this->onError(ErrorFormatter::format($appointmentRequestForm->getErrors()), 'Validation error');
        }

        $appointmentRequest = new AppointmentRequest();
        $appointmentRequest->first_name = $appointmentRequestForm->first_name;
        $appointmentRequest->last_name = $appointmentRequestForm->last_name;
        $appointmentRequest->phone = $appointmentRequestForm->phone;
        $appointmentRequest->parent_first_name = $appointmentRequestForm->parent_first_name;
        $appointmentRequest->parent_last_name = $appointmentRequestForm->parent_last_name;
        $appointmentRequest->dob = $appointmentRequestForm->dob;
        $appointmentRequest->source = AppointmentRequest::SOURCE_KID_SMILE_BOT;
        $appointmentRequest->status = AppointmentRequest::STATUS_NEW;

        if ($appointmentRequest->save()) {
            return $this->onSuccess('Appointment request created');
        }

        return $this->onError(ErrorFormatter::format($appointmentRequest->getErrors()), 'Validation error');
    }
}
