<?php

/** @var $data array */

$this->title = 'Статистика по дням';

?>

<div class="s-day"><h3 class="s-day-title">Статистика по дням</h3>
    <div class="s-day-wrapper">
        <div class="s-day-wrapper-card">
            <div class="card-top"><img src="/img/scheduleNew/s-day1.svg" alt="">
                <h3 class="card-top-title">Количество счетов</h3></div>
            <div class="card-bottom">
                <div class="card-bottom-info"></div>
                <div class="card-bottom-info" style="text-align: right">
                    <h3 class="subTitle"><?= $data['invoices_count'] ?></h3>
                </div>
            </div>
        </div>
        <div class="s-day-wrapper-card">
            <div class="card-top"><img src="/img/scheduleNew/s-day2.svg" alt="">
                <h3 class="card-top-title">Сумма счетов</h3></div>
            <div class="card-bottom">
                <div class="card-bottom-info"></div>
                <div class="card-bottom-info" style="text-align: right">
                    <h3 class="subTitle">
                        <?= number_format($data['invoices_total'], 0, ' ', ' ') ?> сум
                    </h3>
                </div>
            </div>
        </div>
        <div class="s-day-wrapper-card">
            <div class="card-top"><img src="/img/scheduleNew/s-day3.svg" alt="">
                <p class="card-top-green">Оплачено</p>
                <h3 class="card-top-title-green">
                    <?= number_format($data['pay_total']['all'], 0, ' ', ' ') ?> сум
                </h3>
            </div>
<!--            <div class="card-bottom">-->
<!--                <div class="card-bottom-info">-->
<!--                    <span class="title">Без наличкой</span>-->
<!--                    <h3 class="subTitle">-->
<!--                        --><?php //= number_format($data['pay_total']['card'], 0, ' ', ' ') ?><!-- сум-->
<!--                    </h3>-->
<!--                </div>-->
<!--                <div class="card-bottom-info">-->
<!--                    <span class="title">Наличкой</span>-->
<!--                    <h3 class="subTitle">-->
<!--                        --><?php //= number_format($data['pay_total']['cash'], 0, ' ', ' ') ?><!-- сум-->
<!--                    </h3>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <div class="s-day-wrapper-card">
            <div class="card-top"><img src="/img/scheduleNew/s-day4.svg" alt="">
                <h3 class="card-top-title" style="color: #DA1E28">Долги</h3></div>
            <div class="card-bottom">
                <div class="card-bottom-info"></div>
                <div class="card-bottom-info" style="text-align: right">
                    <h3 class="subTitle" style="color: #DA1E28">
                        <?= number_format($data['total_debt'], 0, ' ', ' ') ?> сум
                    </h3>
                </div>
            </div>
        </div>
        <div class="s-day-wrapper-card">
            <div class="card-top"><img src="/img/scheduleNew/s-day5.svg" alt="">
                <h3 class="card-top-title">Общее количество страховки</h3></div>
            <div class="card-bottom">
                <div class="card-bottom-info"></div>
                <div class="card-bottom-info" style="text-align: right">
                    <h3 class="subTitle">
                        <?= $data['insurance_invoices_count'] ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="s-day-wrapper-card">
            <div class="card-top"><img src="/img/scheduleNew/s-day6.svg" alt="">
                <h3 class="card-top-title">Общая сумма страховок</h3></div>
            <div class="card-bottom">
                <div class="card-bottom-info"></div>
                <div class="card-bottom-info" style="text-align: right">
                    <h3 class="subTitle">
                        <?= number_format($data['insurance_invoices_total'], 0, ' ', ' ') ?> сум
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>