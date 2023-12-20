<?php

/** @var \common\models\DailyReport $dailyReports */

?>

<div class="report__wrapper-card-item">

    <div>
        <div class="card__item-head">
            <div class="card__item-left">
                <p>СЧЕТА И ФИНАНСЫ</p>
            </div>
            <div class="card__item-right">
                <img src="/img/excel.svg" alt="">
                <img src="/img/print.svg" alt="">
                <img src="/img/full.svg" alt="">
            </div>
        </div>

        <div class="card__item-body">
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Сумма выставленных счетов
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= number_format($dailyReports->getInvoicesTotal(),0,' ',' '); ?> сум</p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Общий приход денег за день
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= number_format($dailyReports->getIncomingTotal(),0,' ',' '); ?> сум</p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Количество выставленных счетов
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= $dailyReports->getInvoices(); ?></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Количество аннулированных счетов
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= $dailyReports->getCancelledInvoices(); ?></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Оплачено по выставленным за день
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= $dailyReports->getPaidInvoices(); ?></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Долг за день
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p class="text__red"><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Аванс за день
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Погашение долга за прошлые даты
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p class="text__red"><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Сумма расходов за день
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Итого за день
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p class="text__red"><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card__item-footer">
        <a href="">Посмотреть детали ></a>
    </div>

</div>