<?php

/** @var Invoice $invoices */

/** @var array $data */

use common\models\constants\UserRole;
use common\models\Invoice;
use yii\helpers\Url;

$this->title = 'Список должников';

?>
<div class="patients">
    <div style="display: flex; justify-content: space-between">
        <div>
            <h2>Список должников</h2>
        </div>
        <div>
            <a href="<?= Url::to(
                ['cashier/excel-patient-debts', 'startDate' => $data['start_date'], 'endDate' => $data['end_date']]
            ) ?>" class="patient-debt-excel-btn">
                экспортировать
                <img src="/img/svg/excel_white.svg" alt="excel">
            </a>
        </div>
    </div>
    <div class="patients__table" style="overflow-y: auto">
        <table>
            <tr>
                <td>
                    <div class="filter_text">
                        <span>Инвойс №</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата визита</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Пациент фио</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Доктор фио</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Общая сумма долга</span>
                    </div>
                </td>
            </tr>

            <?php
            foreach ($invoices as $invoice): ?>
                <tr>
                    <td>
                        <p><?= $invoice->getInvoiceNumber() ?></p>
                    </td>
                    <td>
                        <?= $invoice->getVisit() ?>
                    </td>
                    <td>
                        <a href="<?= Url::to([
                            Yii::$app->user->can('cashier') == UserRole::ROLE_CASHIER
                                ? 'patient/finance'
                                : 'patient/update',
                            'id' => $invoice->patient_id
                        ]) ?>">
                            <?= $invoice->patient ? $invoice->patient->getFullName() : '-' ?>
                        </a>
                    </td>
                    <td>
                        <?= $invoice->doctor ? $invoice->doctor->getFullName() : '-' ?>
                    </td>
                    <td>
                        <?= number_format($invoice->getRemains(), 0, '', ' ') ?> сум
                    </td>
                </tr>
            <?php
            endforeach; ?>
        </table>
    </div>
</div>
