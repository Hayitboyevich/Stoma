<?php

/* @var $recordsByDoctor array */
/**@var $doctors User */
/**@var $priceLists PriceList */

use common\models\PriceList;
use common\models\User;

$selected_doctor_id = array_key_exists('doctor_id', $_GET) ? $_GET['doctor_id'] : 0;

?>

<div class="day-trick">
    <div class="day-trick_top">
        <?= $this->render('_trick-top.php', [
            'doctors' => $doctors,
            'selected_doctor_id' => $selected_doctor_id,
        ]) ?>
    </div>

    <div class="day-trick_calendar">
        <?= $this->render('_week-trick-calendar', [
            'recordsByDoctor' => $recordsByDoctor
        ]) ?>
    </div>

    <?= $this->render('_new-record-modal', [
        'doctors' => $doctors,
        'priceLists' => $priceLists
    ]) ?>

    <?= $this->render('_record-details') ?>
</div>
