<?php

namespace api\traits;

use yii\web\Response;

trait ApiHelper
{
    protected function onSuccess($message = '', int $code = 200): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->setStatusCode($code);

        return [
            'status' => true,
            'errors' => [],
            'message' => $message
        ];
    }

    protected function onError($errors, $message = '', int $code = 200): array
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->setStatusCode($code);

        return [
            'status' => false,
            'errors' => $errors,
            'message' => $message
        ];
    }
}
