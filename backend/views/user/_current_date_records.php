<?php

/** @var array $data */
/** @var Reception $patients */
/** @var Reception $records */

use common\models\Reception;
use yii\helpers\Url;

$this->title = 'Записи за текущий день';

?>
<div class="patients">
    <div class="patients__search">
        <div class="patients__right" style="margin: 10px 0 10px 0;">
            <a class="btn_top btn_blue new-patient-btn" href="<?= Url::to(['user/records']) ?>">
                Текущий неделя
            </a>
        </div>
    </div>
    <div class="patients__table" style="overflow-y: auto">
        <table cellspacing="0">
            <tr>
                <td>
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>ФИО</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Время</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Статус</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($records as $patient): ?>
                <tr>
                    <td>
                        <p><?= $patient->id ?></p>
                    </td>
                    <td>
                        <p><?= $patient->patient->firstname . ' ' . $patient->patient->lastname ?></p>
                    </td>
                    <td>
                        <p>с <?= substr($patient->record_time_from,0,5) ?> до <?= substr($patient->record_time_to,0,5) ?></p>
                    </td>
                    <td>
                        <p style="color: white;" data-id="<?= $patient->id ?>" data-state="<?= $patient->state ?>" class="single-record-card <?= $patient->getStatusClass()[$patient->state]. '_a' ?? '' ?>">
                            <?= $patient->getStatus()[$patient->state] ?? '' ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?= $this->render('../reception/_record-details') ?>

<input type="hidden" id="date-start-filter" value="<?= $data['date'] ?>" />
<input type="hidden" id="date-end-filter" value="<?= $data['date'] ?>" />
