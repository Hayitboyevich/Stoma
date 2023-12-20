<?php
/* @var $this \yii\web\View */
?>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="#" class="nav__logo">
                <img class="nav__logo_icon" src="/img/logoIcon.svg" alt="">
                <span class="nav__logo-name">
                    <img src="/img/logo.svg" alt="">
                </span>
            </a>
            <?= \common\widgets\Menu::widget([
                'options'       => ['class' => 'nav__list', 'tag' => 'div'],
                'itemOptions'   => ['class' => 'nav__link'],
                'labelTemplate' => '<span class="nav__name">{label}</span>',
                'items'         => [
                    [
                        'label'   => 'Прием',
                        'url'     => ['/reception/index'],
                        'visible' => Yii::$app->user->can('reception_list'),
                        'icon'    => 'calendar'
                    ],
                    ['label' => 'Пациенты', 'url' => ['/patient/index'], 'icon' => 'users'],
                    ['label' => 'Прайс-лист', 'url' => ['/price-list/index'], 'icon' => 'report'],
                    ['label' => 'Заявки пациентов', 'url' => ['/callback/index'], 'icon' => 'tooth'],
                    ['label' => 'Аккаунты для входа', 'url' => ['/user/index'], 'icon' => 'report'],
                    ['label' => 'Роли', 'icon' => 'settings', 'url' => ['/auth-item/role']]
                ]
            ]) ?>


            <a href="#" class="nav__link" id="header-toggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="6" y="6" width="12" height="12" rx="1" fill="#C3C7D1"/>
                    <rect x="8" y="8" width="3" height="8" rx="1" fill="white"/>
                </svg>
                <span class="nav__name">Закрыть</span>
            </a>
    </nav>
</div>
