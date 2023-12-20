<?php

/** @var $model User */

/** @var $data array */

use common\models\Invoice;
use common\models\User;
use yii\helpers\Url;

$this->title = $model->getFullName();

?>

<div class="schedule_new">
    <h3 class="schedule_new-title"><?= $model->getFullName() ?></h3>
    <form class="schedule_new-select">
        <div class="schedule_new-select-left">
            <label class="schedule_new-select-left-label">
                с
                <input type="date" name="start_date" value="<?= $data['start_date'] ?>">
            </label>
            <label class="schedule_new-select-left-label">
                по
                <input type="date" name="end_date" value="<?= $data['end_date'] ?>">
            </label>
            <button type="submit" class="btn-reset btn-show">показать</button>
        </div>
        <div class="schedule_new-select-right">
            <?php
            $startDate = Yii::$app->request->get('start_date');
            $endDate = Yii::$app->request->get('end_date');
            $isToday = $startDate === $endDate;
            $isMonth = $startDate === date('Y-m-01') && $endDate === date('Y-m-t');
            ?>
            <a href="<?= Url::to(['cashier/dashboard', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]) ?>"
               class="btn-reset btn__schedule <?= $isToday ? 'active' : '' ?>">
                Сегодня
            </a>
            <a href="<?= Url::to(['cashier/dashboard', 'start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]) ?>"
               class="btn-reset btn__schedule <?= $isMonth ? 'active' : '' ?>">
                Текущий месяц
            </a>
        </div>
    </form>
    <div class="schedule_new-cards">
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Visa</p>
                <p class="number"><?= number_format($data['statistics']['visa'], 0, ' ', ' ') ?> Сум</p>
            </div>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Mastercard</p>
                <p class="number"><?= number_format($data['statistics']['mastercard'], 0, ' ', ' ') ?> Сум</p>
            </div>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Click</p>
                <p class="number"><?= number_format($data['statistics']['click'], 0, ' ', ' ') ?> Сум</p>
            </div>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Humo</p>
                <p class="number"><?= number_format($data['statistics']['humo'], 0, ' ', ' ') ?> Сум</p>
            </div>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Payme</p>
                <p class="number"><?= number_format($data['statistics']['payme'], 0, ' ', ' ') ?> Сум</p>
            </div>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Uzcard</p>
                <p class="number"><?= number_format($data['statistics']['uzcard'], 0, ' ', ' ') ?> Сум</p>
            </div>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Налич.</p>
                <p class="number"><?= number_format($data['statistics']['cash'], 0, ' ', ' ') ?> Сум</p>
            </div>
            <span class="boxes_icon">
              <img src="/img/scheduleNew/7.svg" alt="">
            </span>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">Переч.</p>
                <p class="number"><?= number_format($data['statistics']['transfer'], 0, ' ', ' ') ?> Сум</p>
            </div>
            <span class="boxes_icon">
              <img src="/img/scheduleNew/8.svg" alt="">
            </span>
        </div>
        <div class="boxes">
            <div class="boxes_header">
                <p class="title">В долларах</p>
                <p class="number dollar">
                    <?= number_format($data['statistics']['foreign_currency'], 0, ' ', ' ') ?>$
                </p>
            </div>
            <span class="boxes_icon icon-dollar">
              <img src="/img/scheduleNew/icon-dollar.svg" alt="">
            </span>
        </div>
    </div>
</div>
