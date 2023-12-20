<?php

use common\models\DoctorSchedule;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var $doctorSchedules DoctorSchedule */

$this->title = 'Список';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="list-status">
    <div class="list-status-header"><h3 class="list-status__title">Список</h3>
        <a href="<?= Url::to(['doctor-schedule/create', 'doctor' => Yii::$app->request->get('id')]) ?>" class="btn-reset btn__blue">
            Новый <span><img src="/img/scheduleNew/IconAdd.svg" alt=""></span>
        </a>
    </div>
    <!--  table -->
    <div class="list-status__table" style="height:calc(100% - 95px); overflow-y: auto; ">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text"><span>#ID</span></div>
                </td>
                <td class="table_head">
                    <div class="filter_text"><span>С</span></div>
                </td>
                <td class="table_head">
                    <div class="filter_text"><span>По</span></div>
                </td>
                <td class="table_head">
                    <div class="filter_text"><span>Актуальный</span></div>
                </td>
                <td class="table_head">
                    <div class="filter_text"><span>Действие</span></div>
                </td>
            </tr>
            <?php foreach($doctorSchedules as $doctorSchedule): ?>
                <tr>
                    <td class="table__body-td"><p>1</p></td>
                    <td class="table__body-td">
                        <p><?= date('d.m.Y', strtotime($doctorSchedule->date_from)) ?></p>
                    </td>
                    <td class="table__body-td">
                        <p><?= date('d.m.Y', strtotime($doctorSchedule->date_to)) ?></p>
                    </td>
                    <td class="table__body-td">
                        <?php if ($doctorSchedule->current === DoctorSchedule::ACTIVE_SCHEDULE): ?>
                            <p class="status green">Актуальный</p>
                        <?php else: ?>
                            <button type="button" data-id="<?= $doctorSchedule->id ?>" class="status blue btn-reset make-actual">
                                Сделать актуальным
                            </button>
                        <?php endif; ?>
                    </td>
                    <td class="table__body-td">
                        <div class="table__body-td-icon">
                            <a href="<?= Url::to(['user/timetable', 'doctor_schedule_id' => $doctorSchedule->id]) ?>">
                                <img src="/img/scheduleNew/eye.svg" alt="">
                            </a>
                            <a href="<?= Url::to(['doctor-schedule/update', 'id' => $doctorSchedule->id, 'doctor' => $doctorSchedule->doctor_id]) ?>">
                                <img src="/img/scheduleNew/IconEdit.svg" alt="">
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
