<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppNewAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

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
        <!--   mobile__wrapper-active  -->
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= $this->render('mobile-menu', ['current_route' => $current_route]) ?>
        <?php endif ?>
        <?= $this->render('header', ['current_route' => $current_route]) ?>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= $this->render('navbar', ['current_route' => $current_route]) ?>
        <?php endif; ?>

        <div class="wrapper__box">
            <?= Alert::widget() ?>
            <?= $content ?>

        </div>
    </div>
    <script>
        //$(document).ready(function () {
        //    $('.mobile__wrapper-menu li').each(function () {
        //        if ($(this).find('a').attr('href') == '<?php //= $current_route ?>//') {
        //            $(this).addClass('active');
        //        }
        //    });
        //});
        // link mobile
        const mobileLinks = document.querySelectorAll('.list__wrapper-item-link');
        mobileLinks.forEach((link) => {
            link.addEventListener('click', () => {
                mobileLinks.forEach((link) => link.classList.remove('active'));
                link.classList.add('active');
            });
        });


        const mobileWrapper = document.querySelector('.mobile__wrapper');
        const mobileMenu = document.querySelector('.mobile__wrapper-box');
        const headerToggle = document.querySelector('.header__toggle');
        const mobileCloseIcon = document.querySelector('.mobile__wrapper-close');

        headerToggle.addEventListener('click', () => {
            mobileWrapper.classList.toggle('mobile__wrapper-active');
            mobileMenu.classList.toggle('mobile__wrapper-box-active');
        });

        mobileCloseIcon.addEventListener('click', () => {
            mobileWrapper.classList.remove('mobile__wrapper-active');
            mobileMenu.classList.remove('mobile__wrapper-box-active');
        });

        // click outside mobileWrapper to close it

        window.addEventListener('click', (e) => {
            if (e.target === mobileWrapper) {
                mobileWrapper.classList.remove('mobile__wrapper-active');
                mobileMenu.classList.remove('mobile__wrapper-box-active');
            }
        });

    </script>
    <div id="loader-main">
        <img src="/img/loading3.gif"/>
    </div>
    <?= $this->render('flash-message') ?>
    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage();
