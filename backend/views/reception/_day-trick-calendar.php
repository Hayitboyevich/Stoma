<?php

/* @var $selected_doctor_id int */

/* @var $date string */

use common\models\Media;
use common\models\Reception;
use common\models\User;
use yii\helpers\StringHelper;

?>


<div class="adoptive-trick">
    <div class="adoptive-trick_calendar">
        <?= $this->render('_trick-calendar-header', ['date' => $date]) ?>

        <div class="adoptive-trick_calendar_card">
            <div class="adoptive-trick_calendar_card-wrapper">
                <?php foreach (Reception::getScheduleRecords(['selected_doctor_id' => $selected_doctor_id, 'date' => $date]) as $record_index => $doctor): ?>
                <?php
                    $record_ids = explode(',', $doctor['record_ids']);
                    $records = Reception::find()
                        ->where(['IN', 'id', $record_ids])
                        ->orderBy(['category_id' => SORT_ASC, 'record_time_from' => SORT_ASC, 'duration' => SORT_ASC])
                        ->all();
                ?>
                    <div class="calendar_card__item">
                        <p class="calendar_card__item-title">
                            <?= $doctor['section'] ?>
                        </p>
                        <div class="calendar_card__item-top">
                            <div class="calendar_card__item-top-img">
                                <img src="<?= Media::getFilePath($doctor['file_id'], $doctor['file_type']) ?>" alt="">
                            </div>
                            <div>
                                <h3 class="calendar_card__item-top-name">
                                    <?= $doctor['lastname'] ?> <?= $doctor['firstname'] ?>
                                </h3>
                                <span class="calendar_card__item-top-adoptive">
                                    <?= User::getDoctorTodaySchedule($doctor['doctor_id']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="calendar_card__item-body">
                            <?php foreach ($records as $index => $record): ?>
                                <div class="card__item_box"
                                     data-minutes="<?= substr($record->record_time_from, 3, 2) ?>"
                                     data-hour="<?= substr($record->record_time_from, 0, 2) ?>"
                                     data-duration="<?= $record->duration ?>">
                                   <div style="height: 100%;">
                                       <div class="card__item_user card__item_user_light single-record-card <?= $record->patient->getDebt() > 0 ? 'debt' : '' ?>" data-id="<?= $record->id ?>">
                                           <div style="flex: 1; display: flex; align-items: flex-start; flex-direction: column;column-gap: 5px">
                                               <div style="display: flex; align-items: flex-start; column-gap: 5px; flex: 1">
                                                   <div >
                                                       <h2 class="user__title">
                                                           <?= $record->patient->getFullName() ?>
                                                       </h2>
                                                       <p class="user__subTitle">
                                                           с <?= substr($record->record_time_from, 0, 5) ?>
                                                           до <?= substr($record->record_time_to, 0, 5) ?>
                                                       </p>
                                                   </div>
                                                   <div class="tooltip__wrapper">
                                                       <?php if (!empty($record->patient->note)): ?>
                                                           <span class="tooltips tooltip__info">
                                                            <img src="/img/scheduleNew/icon-1.svg" alt="">
                                                            <p class="tooltip__text tooltip__info-text">
                                                                <?= $record->patient->note ?>
                                                            </p>
                                                     </span>
                                                       <?php endif; ?>
                                                       <?php if ($record->patient->getDebt() > 0): ?>
                                                           <span class="tooltips tooltip__money">
                                                            <img src="/img/scheduleNew/icon-2.svg" alt="">
                                                            <p class="tooltip__text tooltip__money-text">
                                                                <?= number_format($record->patient->getDebt(), 0, '', ' ') ?> сум
                                                            </p>
                                                       </span>
                                                       <?php endif; ?>
                                                   </div>
                                               </div>
                                               <p class="comment_reception">
                                                   <?= StringHelper::truncate($record->comment, 20) ?>
                                               </p>
                                           </div>

                                           <div class="card__item_user-edit">
                                               <img src="/img/pen_icon.svg" title="Редактировать" class="edit-reception-record" data-id="<?= $record->id ?>"/>
                                               <img src="/img/remove_icon.svg" title="Удалить" class="remove-record" data-id="<?= $record->id ?>"/>
                                           </div>
                                       </div>
                                   </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="calendar_card__item-add">
                            <button class="btn-reset add__btn doctor-add-new-record" data-doctor-id="<?= $doctor['doctor_id'] ?>"
                                    data-section-id="<?= $doctor['section_id'] ?>">
                                Записать пациента <img src="/img/icon_plusOrange.svg" alt="">
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
