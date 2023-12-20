<?php

namespace api\helpers;

class ErrorFormatter
{
    public static function format($errors): array
    {
        $result = [];
        foreach ($errors as $key => $message) {
            $result[] = [
                'key' => $key,
                'message' => $message[0]
            ];
        }
        return $result;
    }
}
