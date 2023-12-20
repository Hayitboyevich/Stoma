<?php /* @var $model common\models\User */ ?>

<div class="new-schedule-form">
    <div>
        <label>Дата от:</label>
        <input type="date" name="schedule_date_from" />
        <input type="hidden" name="doctor_id" value="<?= $model->id ?>" />

        <label>Дата до:</label>
        <input type="date" name="schedule_date_to" />
        <button class="save-doctor-schedule">Сохранить</button>
    </div>
</div>