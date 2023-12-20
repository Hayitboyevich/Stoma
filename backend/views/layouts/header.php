<?php

/** @var $current_route */

use common\models\AppointmentRequest;
use yii\helpers\Html;
use yii\helpers\Url;

$session = Yii::$app->session;
?>
<header class="header <?= $session->has('navbar') && $session->get('navbar') === 'active' ? 'body-pd' : '' ?>"
        id="header">
    <div class="header__toggle" >
       <div style="display:flex; justify-content: center; align-items: center; height: 100%">
           <img src="/img/scheduleNew/burgerMenu.svg" alt="">
       </div>
    </div>

    <?php
    if (!Yii::$app->user->isGuest): ?>
        <div class="header__right">
            <?php
            $permissions = Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->identity->id);
            $mapped = array_map(static function ($item) {
                return $item->name;
            }, $permissions, array_keys($permissions));

            $menuPermissions = [
                'delete_log_index',
                'auth_item_role',
                'config_index',
                'telegram_notification_index',
                'sms_notification_index'
            ];
            ?>

            <?php
            if (!empty(array_intersect($mapped, $menuPermissions))): ?>
                <div class="navbar">
                    <div class="user-info">
                        <div onclick="onSettings()" class="info" id="infoBtn">
                            <div class="info-left">
                                <div class="texts">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.8824 8.11765H5.05882C4.45882 8.11765 4 7.65882 4 7.05882C4 6.45882 4.45882 6 5.05882 6H19.8824C20.4824 6 20.9412 6.45882 20.9412 7.05882C20.9412 7.65882 20.4824 8.11765 19.8824 8.11765ZM19.8824 13.0588H5.05882C4.45882 13.0588 4 12.6 4 12C4 11.4 4.45882 10.9412 5.05882 10.9412H19.8824C20.4824 10.9412 20.9412 11.4 20.9412 12C20.9412 12.6 20.4824 13.0588 19.8824 13.0588ZM19.8824 18H5.05882C4.45882 18 4 17.5412 4 16.9412C4 16.3412 4.45882 15.8824 5.05882 15.8824H19.8824C20.4824 15.8824 20.9412 16.3412 20.9412 16.9412C20.9412 17.5412 20.4824 18 19.8824 18Z"
                                              fill="black"/>
                                    </svg>
                                    <h1 class="name">Меню</h1>
                                </div>
                            </div>
                        </div>
                        <div id="settings" class="info-items">
                            <?php
                            if (Yii::$app->user->can('delete_log_index')): ?>
                                <a href="<?= Url::to(['delete-log/index']) ?>" class="item" title="История удалений">
                                    <img src="/img/svg/remove_icon.svg"/>
                                    История удалений
                                </a>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::$app->user->can('auth_item_role')): ?>
                                <a href="<?= Url::to(['auth-item/role']) ?>" class="item" title="Роли">
                                    <img src="/img/svg/role.svg"/>
                                    Роли
                                </a>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::$app->user->can('config_index')): ?>
                                <a href="<?= Url::to(['config/index']) ?>" class="item" title="Скидка">
                                    <img src="/img/svg/discount.svg"/>
                                    Скидка
                                </a>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::$app->user->can('telegram_notification_index')): ?>
                                <a href="<?= Url::to(['telegram-notification/index']) ?>" class="item"
                                   title="Журнал Telegram">
                                    <img src="/img/svg/journal-telegram.svg"/>
                                    Журнал Telegram
                                </a>
                            <?php
                            endif; ?>
                            <?php
                            if (Yii::$app->user->can('sms_notification_index')): ?>
                                <a href="<?= Url::to(['sms-notification/index']) ?>" class="item" title="Журнал СМС">
                                    <img src="/img/svg/journal-sms.svg"/>
                                    Журнал СМС
                                </a>
                            <?php
                            endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            endif ?>

            <?php
            if (Yii::$app->user->can('appointment_request_index')): ?>
                <a href="<?= Url::to(['appointment-request/index']) ?>">
                    <div class="header__notif">
                        <span><img src="/img/notif.svg" alt=""></span>
                        <?php
                        $unread_requests_count = AppointmentRequest::getUnreadCount(); ?>
                        <?php
                        if ($unread_requests_count > 0): ?>
                            <a href="/appointment-request/index" class="new-messages-count">
                                <?= $unread_requests_count ?>
                            </a>
                        <?php
                        endif; ?>
                    </div>
                </a>

            <?php
            endif; ?>
            <div class="header__user">
                <div class="header__img">
                    <img src="<?= Yii::$app->user->identity->media
                        ? '/media/download?id=' . Yii::$app->user->identity->media->id . '.' . Yii::$app->user->identity->media->file_type
                        : '/img/default-avatar.png'; ?>" alt="">
                </div>
                <img src="/img/header_downIcon.svg" alt="">
                <div class="user-header-content">
                    <h2 class="current-user-initials"><?= Yii::$app->user->identity->lastname; ?> <?= Yii::$app->user->identity->firstname; ?></h2>
                    <div class="profile-content">
                        <ul class="list-reset profile-content-list">
                            <a class="list-reset-item" href="<?= Url::to(['profile/index']) ?>">
                                <input type="hidden" id="user-role" value="<?= Yii::$app->user->identity->role ?>"/>
                                <input type="hidden" id="navbar-setting"
                                       value="<?= Yii::$app->session->has('navbar') ? Yii::$app->session->get(
                                           'navbar'
                                       ) : 'inactive' ?>"/>
                                <img src="/img/scheduleNew/user-icon.svg" alt="" width="20" height="20">
                                <p class="list-reset-item-text">
                                    Личная информация
                                </p>
                            </a>
                            <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                            . Html::submitButton('
                                <img src="/img/scheduleNew/logout2.svg" alt="" width="20" height="20">
                                <p class="list-reset-item-text">
                                    Выйти
                                </p>
                            ', ['class' => 'btn-reset list-reset-item', 'style' => 'width: 100%;'])
                            . Html::endForm() ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php
    else: ?>
        <?php
        if (!in_array($current_route, ['site/login', 'site/reset-password'])) {
            Yii::$app->response->redirect(['site/login']);
        } ?>
    <?php
    endif; ?>
</header>
