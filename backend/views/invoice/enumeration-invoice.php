<?php

/** @var Invoice $invoices */

use common\models\Invoice;
use yii\helpers\Url;

$this->title = 'Инвойсы по перечислению';

?>

<div class="employees-list invoices-transfer">
    <p class="title">Инвойсы по перечислению</p>

    <div class="table_wrapper">
        <table>
            <thead>
            <tr>
                <th>Инвойс номер</th>
                <th>Пациент ФИО</th>
                <th>Сумма</th>
                <th>Дата визита</th>
                <th>Дата закрытия</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
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
                                <div class="btn_wrapper">
                                    <button type="button" class="accept-invoice-btn accept_btn btn-reset"
                                            data-id="<?= $invoice->id ?>">
                                        Оплатить
                                    </button>
                                </div>
                            <?php else: ?>
                                <p class="table__body-td-green">Закрыто</p>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->render('_accept-invoice-modal') ?>
