<?php

/** @var yii\web\View $this */

use common\models\Callback;
use common\models\Patient;
use common\models\Reception;

$this->title = 'stomaservice.uz - Главная';
?>
<div class="site-index">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Главная</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-header">Пациенты</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= Patient::find()->count(); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-4">
                    <div class="card-header">Заявка пациентов на запись</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-4">
                    <div class="card-header">Записи</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= Reception::find()->count(); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
