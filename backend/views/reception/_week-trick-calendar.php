<?php

use common\models\Reception;
use yii\helpers\StringHelper;

/* @var $recordsByDoctor array */

?>

<div class="day-trick_calendar__header">
    <?php foreach (Reception::WEEKDAYS as $day): ?>
        <div class="item">
            <p class="<?= date('N') === $day ? 'active' : '' ?>"><?= $day ?></p>
        </div>
    <?php endforeach; ?>
</div>


<?php if (!empty($recordsByDoctor)): ?>
    <?php foreach ($recordsByDoctor as $keyRecord => $record): ?>
        <div class="day-trick_calendar__body">
            <?php foreach (array_slice($record, 1) as $key => $recordDates): ?>
                <div class="body-card">
                    <div class="body-card__wrapper">
                        <?php if (!empty($recordDates)): ?>
                            <div class="body-card__wrapper-box">
                                <div class="card-user">
                                    <div class="card-user__info">
                                        <img src="<?= $record['doctor']['image'] ?>" alt="">
                                        <h3>
                                            <?= $record['doctor']['name'] ?>
                                            <span style="color: orange"><?= date('d-m-Y', strtotime($key)) ?></span>
                                        </h3>
                                    </div>
                                </div>
                                <?php foreach ($recordDates as $item): ?>
                                    <div class="card__item_box">
                                        <div style="height: 100%;">
                                            <div class="card__item_user card__item_user_light single-record-card <?= $item->patient->getDebt() > 0 ? 'debt' : '' ?>"
                                                 data-id="<?= $item->id ?>">
                                                <div style="flex: 1; display: flex; align-items: flex-start; flex-direction: column;column-gap: 5px">
                                                    <div style="display: flex; align-items: flex-start; column-gap: 5px; flex: 1">
                                                        <div>
                                                            <h2 class="user__title">
                                                                <?= $item->patient->getFullName() ?>
                                                            </h2>
                                                            <p class="user__subTitle">
                                                                с <?= substr($item->record_time_from, 0, 5) ?>
                                                                до <?= substr($item->record_time_to, 0, 5) ?>
                                                            </p>
                                                        </div>
                                                        <div class="week__tooltip">
                                                            <?php if (!empty($item->patient->note)): ?>
                                                                <span class="week__tooltip-item">
                                                                <img src="/img/scheduleNew/icon-1.svg" alt="">
                                                               <div class="week__tooltipChip week__tooltip-text">
                                                                    <p class="text__blue">
                                                                    <?= $item->patient->note ?>
                                                                </p>
                                                               </div>
                                                            </span>
                                                            <?php endif; ?>
                                                            <?php if ($item->patient->getDebt() > 0): ?>
                                                                <span class="week__tooltip-item">
                                                                    <img src="/img/scheduleNew/icon-2.svg" alt="">
                                                                       <div class="week__tooltipChip week__tooltip-text">
                                                                            <p class="text__red">
                                                                            <?= number_format($item->patient->getDebt(), 0, '', ' ') ?> сум
                                                                        </p>
                                                                   </div>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <p class="comment_reception">
                                                        <?= StringHelper::truncate($item->comment, 20) ?>
                                                    </p>
                                                </div>

                                                <div class="card__item_user-edit">
                                                    <img src="/img/pen_icon.svg" title="Редактировать"
                                                         class="edit-reception-record" data-id="<?= $item->id ?>"/>
                                                    <img src="/img/remove_icon.svg" title="Удалить"
                                                         class="remove-record" data-id="<?= $item->id ?>"/>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($key >= date('Y-m-d')): ?>
                                <div class="calendar_card__item-add">
                                    <button class="add__btn btn-reset add__btn doctor-add-new-record"
                                            data-doctor-id="<?= $keyRecord ?>">
                                        Записать пациента <img src="/img/icon_plusOrange.svg" alt="">
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<style>
    .card__item_box {
        padding: 0 2px;
        background-color: #EBF5FE;
        border: 1px solid #84BDF1;
        width: 100%;
        height: 60px;
        position: relative !important;
    }

    .card__item_box .user__title {
        margin: 0;
        font-weight: 600;
        font-size: 11px;
        line-height: 14px;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #0047F9;
    }

    .card__item_box .user__subTitle {
        margin: 0;
        font-weight: 400;
        font-size: 10px;
        line-height: 12px
    }

    .card__item_box .card__item_user {
        padding: 4px 4px 4px 10px;
        min-height: 20px;
        border-radius: 4px;
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        height: calc(100% - 4px);
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between
    }

    .card__item_box .card__item_user-edit {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-column-gap: 5px;
        -moz-column-gap: 5px;
        column-gap: 5px
    }

    .card__item_box .card__item_user-edit img {
        width: 16px;
        height: 16px;
        border: 1px solid #ccc;
        padding: 2px;
        border-radius: 5px;
        cursor: pointer
    }

    .card__item_box .card__item_user:not(:last-child) {
        margin-bottom: 2px
    }

    .card__item_box .card__item_user:after {
        content: "";
        position: absolute;
        left: 4px;
        top: 5px;
        width: 2px;
        height: calc(100% - 10px);
        background-color: #0047F9;
    }

    .week__tooltip {
        display: flex;
        align-items: flex-start;
    }

    .week__tooltip-item {
        display: flex;
        align-items: flex-start;
        position: relative;
    }

    .week__tooltip-text {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        margin: 0;
        opacity: 0;
        visibility: hidden;
        border-radius: 8px;
        padding: 1em 0.4em;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        z-index: 2;
    }

    .week__tooltip-item:hover .week__tooltip-text {
        opacity: 1;
        visibility: visible;
    }

    .week__tooltipChip .text__red {
        margin: 0;
        color: #ff6700;
        font-size: 12px;
        line-height: 14px;
        font-weight: 600;
    }

    .week__tooltipChip .text__blue {
        margin: 0;
        color: #0047F9;
        font-size: 12px;
        line-height: 14px;
        font-weight: 600;
    }

    .card__item_box:not(:last-child) {
        margin-bottom: 2px
    }

    .calendar_card__item-add {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        background-color: #676f84;
        padding: 10px
    }

    .calendar_card__item-add .add__btn {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-column-gap: 5px;
        -moz-column-gap: 5px;
        column-gap: 5px;
        background-color: #ff6700;
        border-radius: 2px;
        font-weight: 600;
        font-size: 14px;
        line-height: 16px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #fff;
        padding: 8px;
        width: 100%
    }
</style>
