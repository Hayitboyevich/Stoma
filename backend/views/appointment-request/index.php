<?php

use common\models\AppointmentRequest;

/* @var array $data */
/* @var $this yii\web\View */

$this->title = 'Записи на приём';

?>
<div class="appointments-table">
    <h2 class="appointments-table-title">Записи на приём</h2>
    <!--  table -->
    <div class="appointments-table__table" style="height:calc(100% - 80px); overflow-y: auto;">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Пациент</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Фамилия</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Имя</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Телефон</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Бот</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Время</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Оператор</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Статус</span>
                    </div>
                </td>
            </tr>

            <?php if (empty($data['appointment_requests'])): ?>
                <tr>
                    <td colspan="9">
                        <p class="text_center-table">Нет данных</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($data['appointment_requests'] as $appointmentRequest): ?>
                <tr class="tr">
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->id ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->source === AppointmentRequest::SOURCE_LIVE_STOMA_BOT
                                ? $appointmentRequest->patient->getFullName()
                                : '' ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->last_name ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->first_name ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->phone ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->getSourceName() ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= date('d.m.Y H:i', strtotime($appointmentRequest->created_at)) ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $appointmentRequest->operator
                                ? $appointmentRequest->operator->getFullName()
                                : '' ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <div class="custom-select">
                            <input type="hidden" name="id" value="<?= $appointmentRequest->id ?>">
                            <select class="operator-handle-select">
                                <?php foreach (AppointmentRequest::STATUS_LIST as $key => $status): ?>
                                    <option <?= $appointmentRequest->status === $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $status ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?= $this->render('_pagination', ['data' => $data]) ?>

</div>