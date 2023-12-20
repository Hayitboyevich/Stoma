<?php

use yii\helpers\Url;

/* @var $doctorSchedules array */

$this->title = 'График работы врачей';

?>
<div class="schedules_new">
    <div class="schedules__day">
        <div class="schedules_calendar">
            <div class="schedules_calendar_card">
                <div class="schedules__day-title">
                    <div class="title__item"><span class="text_blue">врач </span><span class="text__orange">ассистент</span>
                    </div>
                    <div class="title__item"><span class="text_blue">врач </span><span class="text__orange">ассистент</span>
                    </div>
                    <div class="title__item"><span class="text_blue">врач </span><span class="text__orange">ассистент</span>
                    </div>
                    <div class="title__item"><span class="text_blue">врач </span><span class="text__orange">ассистент</span>
                    </div>
                    <div class="title__item"><span class="text_blue">врач </span><span class="text__orange">ассистент</span>
                    </div>
                    <div class="title__item"><span class="text_blue">врач </span><span class="text__orange">ассистент</span>
                    </div>
                </div>
                <div class="schedules_calendar__header">
                    <div class="item_info"><p>ПН</p></div>
                    <div class="item_info"><p>ВТ</p></div>
                    <div class="item_info"><p>СР</p></div>
                    <div class="item_info"><p>ЧТ</p></div>
                    <div class="item_info"><p>ПТ</p></div>
                    <div class="item_info"><p>СБ</p></div>
                </div>
                <div class="card__user-wrapper">
                    <?php foreach ($doctorSchedules as $dayCode => $doctorSchedule): ?>
                        <div class="card__user-wrapper-item">
                            <?php foreach ($doctorSchedule as $doctor): ?>
                                <div class="card__user-wrapper-item-box">
                                    <div class="card__user-wrapper-item-box__user">
                                        <?php if (Yii::$app->user->can('admin')
                                            || Yii::$app->user->can('director')): ?>
                                            <a href="<?= Url::to([
                                                'doctor-schedule/index', 'id' => $doctor['doctor_id'],
                                            ]) ?>">
                                                <p><?= $doctor['lastname'] ?> <?= $doctor['firstname'] ?></p>
                                            </a>
                                        <?php else: ?>
                                            <p><?= $doctor['lastname'] ?> <?= $doctor['firstname'] ?></p>
                                        <?php endif; ?>
                                        <span>с <?= $doctor['time_from'] ?> до <?= $doctor['time_to'] ?></span>
                                    </div>
                                    <?php if (!empty($doctor['assistant'])): ?>
                                        <div class="card__user-wrapper-item-box__user">
                                            <p><?= $doctor['assistant']['lastname'] ?> <?= $doctor['assistant']['firstname'] ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
