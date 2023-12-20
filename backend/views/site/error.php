<?php

/** @var yii\web\View $this */

/** @var string $name */
/** @var string $message */
/** @var Exception $exception */


$this->title = $name;

if ($exception->statusCode == 404) {
    echo $this->render('404');
} else {
    if ($exception->statusCode == 500) {
        echo $this->render('500');
    } else {
        echo $this->render('_default-error');
    }
}