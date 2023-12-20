<?php

/** @var $data array */

use common\models\Reception;

$this->title = 'Записи';

?>

<div class="doctor-records-wrap">
    <div class="doctor-records-wrap-header">
        <a href="/user/records?date=<?= $data['prev_week'] ?>" class="prev-week-link">
            <img src="/img/scheduleNew/IconArrowLeft.svg" alt=""><span class="prev-week-text">Предыдущая неделя</span></a>
        <a href="/user/current-date-record" class="today-link">Текущий день</a>
        <a href="/user/records?date=<?= $data['next_week'] ?>" class="next-week-link">
           <span class="prev-week-text"> Следующая неделя</span> <img src="/img/scheduleNew/IconArrowRight.svg" alt="">
        </a>
    </div>
    <div class="week-days-wrap">
        <?php $counter = 0; ?>
        <?php foreach ($data['weekdays'] as $day_code => $weekday): ?>
            <div class="week-days-wrap-item">
                <div class="week-day-title">
                    <h3 class="week-day-title-day"><?= $weekday['day_name'] ?></h3>
                    <p class="week-day-title-date"><?= date('d M', strtotime($data['all_days'][$counter])) ?></p>
                </div>
                <div class="week-day <?= $data['all_days'][$counter] == date('Y-m-d') ? 'active' : '' ?>">
                    <div style="width: 100%; height: calc(100% - 5px); overflow-y: auto" id="week-day-scroll">
                        <?php $doctor = Reception::getRecordsByDoctor(['doctor_id' => $data['doctor_id'], 'date' => $data['all_days'][$counter]]) ?>
                        <?php if ($doctor): ?>
                            <?php
                            $record_ids = explode(',', $doctor['record_ids']);
                            $records = Reception::find()->where(['IN', 'id', $record_ids])->orderBy(['record_time_from' => SORT_ASC])->all();
                            ?>

                            <?php foreach ($records as $index => $record): ?>
                                <?php $patient = $record->patient; ?>
                                <div
                                        data-id="<?= $record->id ?>"
                                        class="card__item_user card__item_user_light single-record-card <?= $record->getStatusClass()[$record->state] ?>"
                                        data-minutes="<?= substr($record->record_time_from, 3, 2) ?>"
                                        data-hour="<?= substr($record->record_time_from, 0, 2) ?>"
                                        data-duration="<?= $record->duration ?>"
                                        data-state="<?= $record->state ?>"
                                >
                                    <h2 class="patient-name"><?= $patient->getFullName() ?></h2>
                                    <p class="patient-time">с <?= substr($record->record_time_from, 0, 5) ?>
                                        до <?= substr($record->record_time_to, 0, 5) ?></p>
                                    <div class="record-actions-wrap">
                                        <img src="/img/pen_icon.svg" title="Редактировать" class="edit-reception-record"
                                             data-id="<?= $record->id ?>"/>
                                        <img src="/img/remove_icon.svg" title="Удалить" class="remove-record"
                                             data-id="<?= $record->id ?>"/>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $counter++; ?>
        <?php endforeach; ?>

    </div>
</div>

<?= $this->render('../reception/_record-details') ?>
<input type="hidden" id="date-start-filter" value="<?= $data['all_days'][0] ?>"/>
<input type="hidden" id="date-end-filter" value="<?= end($data['all_days']) ?>"/>
