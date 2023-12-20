<?php

/* @var $doctors User */
/* @var $selected_doctor_id int */
/* @var $date string */

use common\models\User;
$this->title = 'Приёмы';

?>

<div class="day-top_select select" >
    <div class="select-pried" >
        <span class="select-pried-title">Период</span>
        <img src="/img/calendar.svg" alt="" class="select-pried-img">
        <select class="form_select" aria-label="Default select example" id="calendarSelect">
            <option value="day" <?= Yii::$app->controller->action->id === 'index' ? 'selected' : '' ?>>День</option>
            <option value="week" <?= Yii::$app->controller->action->id === 'week' ? 'selected' : '' ?>>Неделя</option>
        </select>
    </div>

    <div class="selectCustom">
        <span class="selectCustomTitle">Докторы</span>
        <img src="/img/doctor_icon.svg" alt="" class="selectCustomIcon">
        <select class="js-example-basic-single" name="state" id="reception-doctor-filter">
            <option value="0">Все</option>
            <?php if (!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= $doctor->id ?>" <?= $doctor->id == $selected_doctor_id ? 'selected' : '' ?>>
                        <?= $doctor->getFullName() ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <?php if (Yii::$app->controller->id === 'reception' && Yii::$app->controller->action->id === 'index'): ?>
        <div class="select-pried select-pried-date">
            <span class="select-pried-title">Дата</span>
            <img src="/img/calendar.svg" alt="" class="select-pried-img">

            <input type="date" value="<?= $date ?>" class="reception-date" />
            <input type="hidden" name="reception_current_date" value="<?= $date ?>" />
            <input type="hidden" name="reception_current_doctor_id" value="<?= $selected_doctor_id ?>" />
        </div>
    <?php endif ?>
</div>
<div class="day-trick_top-slider slider">
    <div class="swiper" id="swiper-calendar" style="padding: 10px;">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper"></div>
    </div>

    <div class="calendar-prev btnSwiper"></div>
    <div class="calendar-next btnSwiper"></div>
</div>
<div class="top_btn button">
    <button id="modalBtn" class="btn_top btn_orange" data-path="form-popup">
        Записать пациента
        <img src="/img/icon_plus.svg" alt="">
    </button>
</div>

<?php
$js = <<<JS
    $('#swiper-calendar').on('click', '.swiper-slide', function (e) {
        e.preventDefault();
        
        let date = $(this).data('date');
        let calendar = $('#calendarSelect').val()
        let doctor_id = $('#reception-doctor-filter').val()

        if (calendar === 'day') {
            location.href = '/reception/index?date=' + date + '&doctor_id=' + doctor_id;
        } else {
            let dateArr = date.split('_');
            location.href = '/reception/week?start_date=' + dateArr[0] + '&end_date=' + dateArr[1] + '&doctor_id=' + doctor_id;
        }
    });
JS;
$this->registerJs($js);
?>
