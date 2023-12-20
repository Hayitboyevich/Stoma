<?php
/** @var array $params */
/** @var \common\models\DailyReport $dailyReports */


?>
<div class="report__wrapper-card-item">

    <div>
        <div class="card__item-head">
            <div class="card__item-left">
                <p>Визиты</p>
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
                        Всего визитов
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= $dailyReports->getVisits(); ?></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Количество новых пациентов
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><?= $dailyReports->getNewPatients(); ?></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Количество первичных пациентов, ставших пациент…
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Средняя загрузка врачей
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
            <div class="card__item-body-item">
                <div class="card__item-body-item-left">
                    <p>
                        Средняя загрузка кресел
                    </p>
                </div>
                <div class="card__item-body-item-right">
                    <p><span class="in-progress">[в разработке...]</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card__item-footer">
        <a href="">Посмотреть детали ></a>
    </div>

</div>