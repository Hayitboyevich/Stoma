<?php

/**@var $data array */

/**@var $salary integer */

/**@var $todayStatistics array */

use yii\helpers\Url;

$this->title = 'Сегодняшняя статистика';

?>

<div class="statistics">
    <h3 class="statistics-title">Сегодняшняя статистика</h3>
    <div class="statistics_info">
        <div>
            <div class="statistics_info-card border-green">
                <div class="statistics_info-card-icon">
                    <img src="/img/scheduleNew/statistika1.svg" alt="">
                    <p class="statistics_info-card-icon-title">Оплаченные</p>
                </div>
                <div>
                    <p class="statistics_info-card-price">
                        <?= number_format($todayStatistics['paid'], 0, ' ', ' ') ?> Сум
                    </p>
                </div>
            </div>
            <h3 class="statistics-title statistics_info-title">Зарплата</h3>
        </div>
        <div>
            <div class="statistics_info-card border-red">
                <div class="statistics_info-card-icon">
                    <img src="/img/scheduleNew/statistika2.svg" alt="">
                    <p class="statistics_info-card-icon-title">Неоплаченные</p>
                </div>
                <div>
                    <p class="statistics_info-card-price">
                        <?= number_format($todayStatistics['unpaid'], 0, ' ', ' ') ?> Сум
                    </p>
                </div>
            </div>
        </div>
    </div>
    <form action="<?= Url::to(['user/salary']) ?>">
        <div class="statistics-select">
            <label class="statistics-select-label">
                с
                <input type="date" name="start_date" value="<?= $data['start_date'] ?>">
            </label>
            <label class="statistics-select-label">
                по
                <input type="date" name="end_date" value="<?= $data['end_date'] ?>">
            </label>
            <button type="submit" class="btn-reset btn-show">показать</button>
        </div>
    </form>
    <div class="statistics_price">
        <label class="statistics-select-label">
            Зарплата
            <input class="active" type="text" disabled placeholder="Выберите дату чтобы узнать зарплату"
                   value="<?= number_format($salary, 0, ' ', ' ') . ' сум' ?>">
        </label>
    </div>
</div>
