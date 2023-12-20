<?php

/** @var $current_route string */

use yii\bootstrap4\Html;
use yii\helpers\Url;
?>

<div class="mobile__wrapper">
    <!--   mobile__wrapper-box-active   -->
    <div class="mobile__wrapper-box">
        <div class="mobile__wrapper-header">
            <div>
                <img src="/img/scheduleNew/logo-mobile.png" alt="" width="180">
            </div>
            <div class="mobile__wrapper-close">
                <img class="header__close" src="/img/scheduleNew/IconClose.svg" alt="">
            </div>
        </div>
        <div class="mobile__wrapper-list">
            <ul class="list-reset list__wrapper">
                <?php if (Yii::$app->user->can('reception_index')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['reception/index', 'reception/week']) ? 'active' : '' ?>" href="<?= Url::to(['reception/index']) ?>">
                            <img src="/img/svg/tooth.svg"/>
                            <span>Приёмы</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('cashier_patient_index')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['cashier/patient', 'cashier/finance']) ? 'active' : '' ?>" href="<?= Url::to(['cashier/patient']) ?>">
                            <img src="/img/svg/schedule.svg"/>
                            <span>Сегодняшние пациенты</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('transfer_money')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'cashier/transfer-money-history' ? 'active' : '' ?>" href="<?= Url::to(['cashier/transfer-money-history']) ?>">
                            <img src="/img/svg/money_transfer.svg"/>
                            <span>Перевод</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('patient_index')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['patient/index', 'patient/update']) ? 'active' : '' ?>" href="<?= Url::to(['patient/index']) ?>">
                            <img src="/img/svg/users.svg"/>
                            <span>Пациенты</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('user_index')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['user/index', 'user/update']) ? 'active' : '' ?>" href="<?= Url::to(['user/index']) ?>">
                            <img src="/img/svg/user.svg"/>
                            <span>Сотрудники</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('price_list_manage')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['price-list/index', 'technician-price-list/index']) ? 'active' : '' ?>" href="<?= Url::to(['price-list/index']) ?>">
                            <img src="/img/svg/list.svg"/>
                            <span>Прайс-лист</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('site_schedule')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'site/schedule' ? 'active' : '' ?>" href="<?= Url::to(['site/schedule']) ?>">
                            <img src="/img/svg/schedule.svg"/>
                            <span>Расписание</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('doctor')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'user/records' ? 'active' : '' ?>" href="<?= Url::to(['user/records']) ?>">
                            <img src="/img/svg/schedule.svg"/>
                            <span>Расписание</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('doctor')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'doctor-schedule/index' ? 'active' : '' ?>" href="<?= Url::to(['doctor-schedule/index']) ?>">
                            <img src="/img/svg/list.svg"/>
                            <span>График работы</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('appointment_request_index')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'appointment-request/index' ? 'active' : '' ?>" href="<?= Url::to(['appointment-request/index']) ?>">
                            <img src="/img/svg/callback.svg"/>
                            <span>Заявки на запись</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('view_statistics')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['site/statistics', 'site/view-earnings', 'site/report']) ? 'active' : '' ?>" href="<?= Url::to(['site/statistics']) ?>">
                            <img src="/img/svg/stats.svg"/>
                            <span>Статистика</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('view_statistics_by_day')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'site/statistics-by-day' ? 'active' : '' ?>" href="<?= Url::to(['site/statistics-by-day']) ?>">
                            <img src="/img/svg/statistics_by_day.svg"/>
                            <span>Статистика по дням</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('cashier_stats')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'cashier/stats' ? 'active' : '' ?>" href="<?= Url::to(['cashier/stats']) ?>">
                            <img src="/img/svg/journal.svg"/>
                            <span>Журнал</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('approve_discount')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'discount-request/index' ? 'active' : '' ?>" href="<?= Url::to(['discount-request/index']) ?>">
                            <img src="/img/svg/request-discount.svg"/>
                            <span>Заявки на скидку</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('approve_decline_refund')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'invoice-refund/index' ? 'active' : '' ?>" href="<?= Url::to(['invoice-refund/index']) ?>">
                            <img src="/img/svg/refund.svg"/>
                            <span>Возврат денег</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('view_salary')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'user/salary' ? 'active' : '' ?>" href="<?= Url::to(['user/salary']) ?>">
                            <img src="/img/svg/balance.svg"/>
                            <span>Статистика</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('cashier_stats')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'cashier/dashboard' ? 'active' : '' ?>" href="<?= Url::to(['cashier/dashboard']) ?>">
                            <img src="/img/svg/balance.svg"/>
                            <span>Баланс</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('view_report')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= in_array($current_route, ['cashier/report', 'cashier/patient-debts']) ? 'active' : '' ?>" href="<?= Url::to(['cashier/report']) ?>">
                            <img src="/img/svg/report.svg"/>
                            <span>Отчёт</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('view_insurance_invoices')): ?>
                    <li class="list__wrapper-item">
                        <a class="list__wrapper-item-link <?= $current_route == 'invoice/insurance-invoice' ? 'active' : '' ?>" href="<?= Url::to(['invoice/insurance-invoice']) ?>">
                            <img src="/img/svg/invoice.svg"/>
                            <span>Инвойсы</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="mobile__wrapper-footer">
            <div class="mobile__wrapper-footer-left">
                <div class="mobile__wrapper-footer-img">
                    <img src="<?= Yii::$app->user->identity->media ? '/media/download?id=' . Yii::$app->user->identity->media->id . '.' . Yii::$app->user->identity->media->file_type : '/img/default-avatar.png'; ?>" alt="">
                </div>
                <div>
                    <h4 class="footer_title">
                        <?= Yii::$app->user->identity->lastname; ?> <?= Yii::$app->user->identity->firstname; ?>
                    </h4>
<!--                                                <p class="footer_subtitle">Терапевт</p>-->
                </div>
            </div>
            <div class="mobile__wrapper-footer-right">
                <a href="<?= Url::to(['profile/index']) ?>">
                    <img src="/img/scheduleNew/logout.svg" alt="">
                </a>
            </div>
        </div>
        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
        . Html::submitButton('Выйти', ['class' => 'btn-reset list-reset-item-text footer__logout'])
        . Html::endForm() ?>
    </div>
</div>
