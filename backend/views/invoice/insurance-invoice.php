<?php

/** @var Invoice $invoices */

use common\models\Invoice;
use yii\helpers\Url;

$this->title = 'Страховой инвойс';

?>

<div class="accounting">
    <h3 class="accounting__title">Страховой инвойс</h3>
    <!--  table -->
    <div class="accounting__table" style="height:calc(100% - 95px); overflow-y: auto">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Инвойс номер</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Пациент ФИО</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Сумма</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата визита</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата закрытия</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Действия</span>
                    </div>
                </td>
            </tr>

            <?php foreach($invoices as $invoice): ?>
                <tr>
                    <td class="table__body-td">
                        <p><?= $invoice->getInvoiceNumber() ?></p>
                    </td>
                    <td class="table__body-td">
                        <p><?= $invoice->patient
                                ? "<a href='" . Url::to(['patient/update', 'id' => $invoice->patient_id]) . "'>" . $invoice->patient->getFullName() . "</a>"
                                : '-' ?></p>
                    </td>
                    <td class="table__body-td">
                        <?php if ($invoice->status === Invoice::STATUS_PAID): ?>
                            <p style="color: #27A55D">
                                <?= number_format($invoice->getInvoiceTotal(), 0, '', ' ') ?> сум
                            </p>
                        <?php else: ?>
                            <p style="color: #FF6700">
                                <?= number_format($invoice->getRemains(), 0, '', ' ') ?> сум
                            </p>
                        <?php endif ?>
                    </td>
                    <td class="table__body-td">
                        <p><?= $invoice->getVisit() ?></p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= !empty($invoice->closed_at)
                                ? date('d.m.Y', strtotime($invoice->closed_at))
                                : '-' ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <?php if ($invoice->status === Invoice::STATUS_UNPAID): ?>
                            <button type="button" class="table__body-td-link accept-invoice-btn btn-reset"
                                    data-id="<?= $invoice->id ?>">
                                Оплатить
                            </button>
                        <?php else: ?>
                            <p class="table__body-td-green">Закрыто</p>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?= $this->render('_accept-invoice-modal') ?>
