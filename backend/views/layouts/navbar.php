<?php

/** @var $current_route string */

use yii\helpers\Url;

$session = Yii::$app->session;
?>
<div class="l-navbar <?= $session->has('navbar') && $session->get('navbar') === 'active'
    ? 'show' : '' ?>" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="/" class="nav__logo">
                <img class="nav__logo_icon" src="/img/logoIcon.svg" alt="">
                <span class="nav__logo-name">
                  <img src="/img/logo.svg" alt="">
                </span>
            </a>

            <div class="nav__list">
                <?php
                if (Yii::$app->user->can('reception_index')): ?>
                    <a href="/reception/index"
                       class="nav__link <?= in_array($current_route, ['reception/index', 'reception/week']
                       ) ? 'active' : '' ?>"
                       title="Приёмы">
                        <div class="icon__navbar">
                            <img src="/img/svg/tooth.svg"/>
                        </div>
                        <span class="nav__name">Приёмы</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('cashier_patient_index')): ?>
                    <a href="/cashier/patient"
                       class="nav__link <?= in_array($current_route, ['cashier/patient', 'cashier/finance']
                       ) ? 'active' : '' ?>"
                       title="Сегодняшние пациенты">
                        <div class="icon__navbar">
                            <img src="/img/svg/schedule.svg"/>
                        </div>
                        <span class="nav__name">Сегодняшние пациенты</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('transfer_money')): ?>
                    <a href="/cashier/transfer-money-history"
                       class="nav__link <?= $current_route == 'cashier/transfer-money-history' ? 'active' : '' ?>"
                       title="Перевод">
                        <div class="icon__navbar">
                            <img src="/img/svg/money_transfer.svg"/>
                        </div>
                        <span class="nav__name">Перевод</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('patient_index')): ?>
                    <a href="/patient/index"
                       class="nav__link <?= in_array($current_route, ['patient/index', 'patient/update']
                       ) ? 'active' : '' ?>"
                       title="Пациенты">
                        <div class="icon__navbar">
                            <img src="/img/svg/users.svg"/>
                        </div>
                        <span class="nav__name">Пациенты</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('user_index')): ?>
                    <a href="/user/index"
                       class="nav__link <?= in_array($current_route, ['user/index', 'user/update']) ? 'active' : '' ?>"
                       title="Сотрудники">
                        <div class="icon__navbar">
                            <img src="/img/svg/user.svg"/>
                        </div>
                        <span class="nav__name">Сотрудники</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('price_list_manage')): ?>
                    <a href="/price-list/index"
                       class="nav__link <?= in_array(
                           $current_route,
                           ['price-list/index', 'technician-price-list/index', 'price-list-item/index']
                       ) ? 'active' : '' ?>"
                       title="Прайс-лист">
                        <div class="icon__navbar">
                            <img src="/img/svg/list.svg"/>
                        </div>
                        <span class="nav__name">Прайс-лист</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('site_schedule')): ?>
                    <a href="/site/schedule" class="nav__link <?= $current_route == 'site/schedule' ? 'active' : '' ?>"
                       title="Расписание">
                        <div class="icon__navbar">
                            <img src="/img/svg/schedule.svg"/>
                        </div>
                        <span class="nav__name">Расписание</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('doctor')): ?>
                    <a href="/user/records" class="nav__link <?= $current_route == 'user/records' ? 'active' : '' ?>"
                       title="Расписание">
                        <div class="icon__navbar">
                            <img src="/img/svg/schedule.svg"/>
                        </div>
                        <span class="nav__name">Расписание</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('doctor')): ?>
                    <a href="/doctor-schedule/index"
                       class="nav__link <?= $current_route == 'doctor-schedule/index' ? 'active' : '' ?>"
                       title="График работы">
                        <div class="icon__navbar">
                            <img src="/img/svg/list.svg"/>
                        </div>
                        <span class="nav__name">График работы</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('appointment_request_index')): ?>
                    <a href="/appointment-request/index"
                       class="nav__link <?= $current_route == 'appointment-request/index' ? 'active' : '' ?>"
                       title="Заявки на запись">
                        <div class="icon__navbar">
                            <img src="/img/svg/callback.svg"/>
                        </div>
                        <span class="nav__name">Заявки на запись</span>
                    </a>
                <?php
                endif; ?>
                <?php
                if (Yii::$app->user->can('view_statistics')): ?>
                    <a href="/site/statistics"
                       class="nav__link <?= in_array(
                           $current_route,
                           ['site/statistics', 'site/view-earnings', 'site/report']
                       ) ? 'active' : '' ?>" title="Статистика">
                        <div class="icon__navbar">
                            <img src="/img/svg/stats.svg"/>
                        </div>
                        <span class="nav__name">Статистика</span>
                    </a>
                <?php
                endif; ?>
                <?php
                if (Yii::$app->user->can('view_statistics_by_day')): ?>
                    <a href="<?= Url::to(['site/statistics-by-day']) ?>"
                       class="nav__link <?= $current_route == 'site/statistics-by-day' ? 'active' : '' ?>"
                       title="Статистика по дням">
                        <div class="icon__navbar">
                            <img src="/img/svg/statistics_by_day.svg"/>
                        </div>
                        <span class="nav__name">Статистика по дням</span>
                    </a>
                <?php
                endif; ?>
                <?php
                if (Yii::$app->user->can('cashier_stats')): ?>
                    <a href="/cashier/stats" class="nav__link <?= $current_route == 'cashier/stats' ? 'active' : '' ?>"
                       title="Журнал">
                        <div class="icon__navbar">
                            <img src="/img/svg/journal.svg"/>
                        </div>
                        <span class="nav__name">Журнал</span>
                    </a>
                <?php
                endif; ?>
                <?php
                if (Yii::$app->user->can('request_discount_index')): ?>
                    <a href="/discount-request/index"
                       class="nav__link <?= $current_route == 'discount-request/index' ? 'active' : '' ?>"
                       title="Заявки на скидку">
                        <div class="icon__navbar">
                            <img src="/img/svg/request-discount.svg"/>
                        </div>
                        <span class="nav__name">Заявки на скидку</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('view_salary')): ?>
                    <a href="/user/salary"
                       class="nav__link <?= $current_route == 'user/salary' ? 'active' : '' ?>"
                       title="Статистика">
                        <div class="icon__navbar">
                            <img src="/img/svg/balance.svg"/>
                        </div>
                        <span class="nav__name">Статистика</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('cashier_stats')): ?>
                    <a href="/cashier/dashboard"
                       class="nav__link <?= $current_route == 'cashier/dashboard' ? 'active' : '' ?>"
                       title="Баланс">
                        <div class="icon__navbar">
                            <img src="/img/svg/balance.svg"/>
                        </div>
                        <span class="nav__name">Баланс</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('view_report')): ?>
                    <a href="/cashier/report"
                       class="nav__link <?= in_array($current_route, ['cashier/report', 'cashier/patient-debts']
                       ) ? 'active' : '' ?>" title="Отчёт">
                        <div class="icon__navbar">
                            <img src="/img/svg/report.svg"/>
                        </div>
                        <span class="nav__name">Отчёт</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('view_insurance_invoices')): ?>
                    <a href="/invoice/insurance-invoice"
                       class="nav__link <?= $current_route == 'invoice/insurance-invoice'
                           ? 'active' : '' ?>" title="Инвойсы">
                        <div class="icon__navbar">
                            <img src="/img/svg/invoice.svg"/>
                        </div>
                        <span class="nav__name">Страховой инвойс</span>
                    </a>
                <?php
                endif; ?>

                <?php
                if (Yii::$app->user->can('view_enumeration_invoices')): ?>
                    <a href="/invoice/enumeration-invoice"
                       class="nav__link <?= $current_route == 'invoice/enumeration-invoice'
                           ? 'active' : '' ?>" title="Инвойсы">
                        <div class="icon__navbar">
                            <img src="/img/svg/invoice.svg"/>
                        </div>
                        <span class="nav__name">Инвойсы по пер.</span>
                    </a>
                <?php
                endif; ?>
            </div>
        </div>

        <div style="cursor: pointer" class="nav__link" id="header-toggle">
            <img src="/img/svg/sidebar.svg"/>
            <span class="nav__name">Закрыть</span>
        </div>
    </nav>
</div>
<?php
$permissions = Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->identity->id);
$mapped = array_map(static function ($item) {
    return $item->name;
}, $permissions, array_keys($permissions));
?>
<script>
    let permissions = <?= json_encode($mapped); ?>;
</script>
