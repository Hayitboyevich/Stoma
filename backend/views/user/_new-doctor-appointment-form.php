<?php /* @var $model common\models\User */ ?>

<div class="new-doctor-appointment-form">
    <div>
        <div style="display: flex; align-items: center">
            <p class="input-header-text">День недели: </p> <p class="input-header-week-text" id="appointment-weekday"></p>
        </div>

        <hr>

        <input type="hidden" name="weekday" value=""/>
        <input type="hidden" name="doctor_id" value="<?= $model->id ?>"/>

        <div class="doctor_schedule_item_form">
            <p class="input-header-text">Время работа</p>
            <div class="inline-form-wrap" style="width: 48%">
                <label>от:</label>
                <input type="time" name="appointment_time_from">
            </div>

            <div class="inline-form-wrap" style="width: 48%">
                <label>до:</label>
                <input type="time" name="appointment_time_to">
            </div>
        </div>
        <button class="save-doctor-appointment">Сохранить</button>
    </div>
</div>