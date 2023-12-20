<?php

use common\models\PriceList;
use common\models\Reception;
use common\models\User;

/**@var $record Reception */
/**@var $doctors User */
/**@var $priceLists PriceList */
/**@var $timeFrom string */
/**@var $timeTo string */
/**@var $maxDob string */

$selected_doctor_id = array_key_exists('doctor_id', $_GET) ? $_GET['doctor_id'] : 0;
$date = array_key_exists('date', $_GET) ? $_GET['date'] : date('Y-m-d');

?>
<div class="trick">
    <div class="trick_top">
        <?= $this->render('_trick-top', [
            'selected_doctor_id' => $selected_doctor_id,
            'doctors' => $doctors,
            'date' => $date
        ]) ?>
    </div>
    <div class="trick_calendar">
        <?= $this->render('_day-trick-calendar', [
            'selected_doctor_id' => $selected_doctor_id,
            'date' => $date
        ]) ?>
    </div>

    <?= $this->render('_new-record-modal', [
        'doctors' => $doctors,
        'priceLists' => $priceLists,
        'timeFrom' => $timeFrom,
        'timeTo' => $timeTo,
        'maxDob' => $maxDob
    ]) ?>
    <?= $this->render('_record-details') ?>
</div>
