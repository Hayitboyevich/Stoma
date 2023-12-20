<?php

/* @var $model common\models\User */

/* @var $doctorSchedule common\models\DoctorSchedule */
/* @var $doctor_schedule_item common\models\DoctorScheduleItem */

/* @var $doctor_schedule_id integer */

use common\models\DoctorSchedule;
use common\models\DoctorScheduleItem;

?>

<div class="doctor-schedule">
    <div class="doctor-schedule-days">
        <p class="doctor-schedule-days-text">Понедельник</p>
        <p class="doctor-schedule-days-text">Вторник</p>
        <p class="doctor-schedule-days-text">Среда</p>
        <p class="doctor-schedule-days-text">Четверг</p>
        <p class="doctor-schedule-days-text">Пятница</p>
        <p class="doctor-schedule-days-text">Суббота</p>
    </div>
    <div class="doctor-schedule-wrapper">
        <div class="schedule-hours">
            <div class="schedule-hours-item">08:00</div>
            <div class="schedule-hours-item">09:00</div>
            <div class="schedule-hours-item">10:00</div>
            <div class="schedule-hours-item">11:00</div>
            <div class="schedule-hours-item">12:00</div>
            <div class="schedule-hours-item">13:00</div>
            <div class="schedule-hours-item">14:00</div>
            <div class="schedule-hours-item">15:00</div>
            <div class="schedule-hours-item">16:00</div>
            <div class="schedule-hours-item">17:00</div>
            <div class="schedule-hours-item">18:00</div>
            <div class="schedule-hours-item">19:00</div>
        </div>
        <input type="hidden" id="schedule-id" value="<?= Yii::$app->request->get('doctor_schedule_id') ?>"/>
        <div class="schedule-hours-boxes">
            <?php foreach (DoctorSchedule::WEEKDAYS as $weekday_code => $weekday): ?>
                <div class="week-day doctor-week-day-schedule" data-week-day="<?= $weekday_code ?>">

                    <?php if (!empty($doctor_schedule_id)): ?>
                        <?php
                        $doctor_schedule_items = DoctorScheduleItem::getScheduleItems([
                            'schedule_id' => $doctor_schedule_id,
                            'weekday' => $weekday_code
                        ]);
                        ?>

                        <?php if (!empty($doctor_schedule_items)): ?>
                            <?php foreach ($doctor_schedule_items as $doctor_schedule_item): ?>
                                <div class="doctor-schedule-item"
                                     style="top: <?= $doctor_schedule_item['start_position'] ?>px; height: <?= $doctor_schedule_item['in_minutes'] ?>px;">
                                    <?= $doctor_schedule_item['time_from'] ?> - <?= $doctor_schedule_item['time_to'] ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
        <?= $this->render('_new-schedule-form', ['model' => $model]) ?>
        <?= $this->render('_new-doctor-appointment-form', ['model' => $model]) ?>
    </div>
</div>
