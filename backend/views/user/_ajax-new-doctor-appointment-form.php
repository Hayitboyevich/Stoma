<?php /* @var $doctorScheduleItem common\models\DoctorScheduleItem */ ?>

<p class="input-header-text">Время работа</p>
<div class="inline-form-wrap" style="width: 48%">
    <label>от:</label>
    <input type="time" name="appointment_time_from" value="<?= $doctorScheduleItem->time_from ?? '' ?>">
</div>

<div class="inline-form-wrap" style="width: 48%">
    <label>до:</label>
    <input type="time" name="appointment_time_to" value="<?= $doctorScheduleItem->time_to ?? '' ?>">
</div>
