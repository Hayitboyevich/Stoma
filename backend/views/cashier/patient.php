<?php

/** @var Reception $records */

use common\models\Reception;
use yii\helpers\Url;

$this->title = 'Сегодняшние пациенты';

?>
<div class="today-patients-table">
    <div class="today-patients-header">
        <h2 class="today-patients-title">Сегодняшний пациенты</h2>
    </div>
    <!--  table -->
    <div class="today-patients-table-row" style="height:calc(100% - 62px); overflow-y: auto; padding-bottom: 40px">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
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
                        <span>Врач</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Мобильный номер</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата последного визита</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Скидка</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Сумма счета</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Сумма оплаты</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Статус</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($records as $patient): ?>
                <tr class="tr">
                    <td class="table__body-td">
                        <p>
                            <?= $patient->id ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <a href="<?= Url::to(['patient/finance', 'id' => $patient->patient->id]) ?>">
                                <?= $patient->patient->getFullName() ?>
                            </a>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $patient->doctor->getFullName() ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $patient->patient->phone ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?php if ($patient->patient->last_visited): ?>
                                <?= date('d.m.Y H:i', strtotime($patient->patient->last_visited)) ?>
                            <?php endif; ?>
                        </p>
                    </td>
                    <?php if (Yii::$app->user->can('view_patient_discount')): ?>
                        <td class="table__body-td">
                            <?= !empty($patient->patient->discount)
                                ? '<p class="sale__btn-green">' . $patient->patient->discount . '%</p>'
                                : '<p class="sale__btn-red">0%</p>'
                            ?>
                        </td>
                    <?php endif; ?>
                    <td class="table__body-td">
                        <p>
                            <?= number_format(
                                $patient->invoiceRelation ? $patient->invoiceRelation->getInvoiceTotal() : 0,
                                0,
                                ' ',
                                ' '
                            ) ?> сум
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= number_format($patient->invoiceRelation->transaction->amount ?? 0, 0, ' ', ' ') ?> сум
                        </p>
                    </td>
                    <td class="table__body-td">
                        <button class="btn btn-reset single-record-card <?= $patient->getClasses(
                        )[$patient->state] ?? '' ?>" data-id="<?= $patient->id ?>" data-state="<?= $patient->state ?>">
                            <?= $patient->getStatus()[$patient->state] ?? '' ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!--    pagination -->
<!--    <div class="patients__pagination">-->
<!--        <div class="pagination__left">-->
<!--            <span>1-10</span>-->
<!--            <span>of</span>-->
<!--            <span>97</span>-->
<!--        </div>-->
<!--        <div class="pagination__right">-->
<!--            <div class="pagination_right_select">-->
<!--                <span>Rows per page:</span>-->
<!--                <select>-->
<!--                    <option>10</option>-->
<!--                    <option>10</option>-->
<!--                </select>-->
<!--            </div>-->
<!--            <div class="pagination_right_btn">-->
<!--                <button type="submit">-->
<!--                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                        <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"-->
<!--                              stroke-linejoin="round"/>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <div class="pagination_right_btn_number">-->
<!--                    <span>1/</span>-->
<!--                    <span>10</span>-->
<!--                </div>-->
<!--                <button type="submit" class="button_active">-->
<!--                    <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                        <path d="M1.5 7L4.5 4L1.5 1" stroke="#464F60" stroke-width="1.5" stroke-linecap="round"-->
<!--                              stroke-linejoin="round"/>-->
<!--                    </svg>-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</div>

<?= $this->render('../reception/_record-details') ?>
