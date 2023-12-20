<?php

/** @var yii\web\View $this */

/** @var string $content */

use backend\assets\AppAsset;
use backend\assets\AppNewAsset;
use common\widgets\Alert;
use yii\helpers\Html;

AppAsset::register($this);
AppNewAsset::register($this);
$current_route = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
$session = Yii::$app->session;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="ru" class="page">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/favicon.svg" type="image/x-icon">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="theme-color" content="#111111">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="page__body <?= $session->has('navbar') && $session->get('navbar') == 'active' ? 'body-pd' : '' ?>"
          id="body-pd">

    <?php $this->beginBody() ?>
    <div class="site-container">

        <?= $this->render('header', ['current_route' => $current_route]) ?>
        <?= $this->render('navbar', ['current_route' => $current_route]) ?>

        <div class="wrapper__box">
            <?= Alert::widget() ?>
            <?= $content ?>

        </div>
    </div>
    <div id="loader-main">
        <img src="/img/loading3.gif"/>
    </div>
    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage();
