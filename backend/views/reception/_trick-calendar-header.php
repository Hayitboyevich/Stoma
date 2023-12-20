<?php

use common\models\DoctorSchedule;

/** @var string $date */
?>

<div class="adoptive-trick_calendar__header">
    <div class="item_info"></div>
    <div class="item_info_day">
        <p><?= DoctorSchedule::WEEKDAYS[strtolower(date('D', strtotime($date)))] ?? '' ?></p>
    </div>
</div>
<div class="adoptive-trick_calendar__time-wrapper">
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>9:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>10:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p class="time__active">11:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>12:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>13:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>14:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>15:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>16:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>17:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>18:00</p></div>
        <div class="time_line"><span></div>
    </div>
    <div class="adoptive-trick_calendar_time">
        <div class="adoptive"><p>19:00</p></div>
        <div class="time_line"><span></div>
    </div>
</div>
