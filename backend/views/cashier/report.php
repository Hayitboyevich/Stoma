<?php

/** @var int $invoiceCount */

/** @var int $patientCount */
/** @var int $invoicePendingSum */
/** @var int $invoicePaidCount */
/** @var int $invoiceSum */
/** @var int $invoicePaidSum */

/** @var array $data */

use yii\helpers\Url;

$this->title = 'Отчёт';

?>

<div class="reportNew">
    <h3 class="reportNew-title">Отчёт</h3>
    <div class="reportNew-cards">
        <div class="reportNew-card">
            <div class="reportNew-card-info">
                <img src="/img/scheduleNew/report1.svg" alt="">
                <p class="reportNew-card-info-title">Количество пациентов</p>
            </div>
            <div class="reportNew-card-number"><?= $patientCount ?></div>
        </div>
        <div class="reportNew-card">
            <div class="reportNew-card-info">
                <img src="/img/scheduleNew/report2.svg" alt="">
                <p class="reportNew-card-info-title">Кол-во созданных <br>счётов</p>
            </div>
            <div class="reportNew-card-number"><?= $invoiceCount ?></div>
        </div>
        <div class="reportNew-card">
            <div class="reportNew-card-info">
                <img src="/img/scheduleNew/report3.svg" alt="">
                <p class="reportNew-card-info-title">Сумма созданных <br>счётов</p>
            </div>
            <div class="reportNew-card-number"><?= number_format($invoiceSum, 0, ' ', ' ') ?> Сум</div>
        </div>
        <div class="reportNew-card">
            <div class="reportNew-card-info">
                <img src="/img/scheduleNew/report4.svg" alt="">
                <p class="reportNew-card-info-title">Кол-во оплаченных <br>польностью</p>
            </div>
            <div class="reportNew-card-number"><?= $invoicePaidCount ?></div>
        </div>
        <div class="reportNew-card">
            <div class="reportNew-card-info">
                <img src="/img/scheduleNew/report5.svg" alt="">
                <p class="reportNew-card-info-title">Сумма оплаченных <br>счётов</p>
            </div>
            <div class="reportNew-card-number">
                <?= number_format($invoicePaidSum, 0, ' ', ' ') ?> Сум
            </div>
        </div>
        <a href="<?= Url::to(
            ['cashier/patient-debts', 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]
        ) ?>" class="reportNew-card">
            <div class="reportNew-card-info">
                <img src="/img/scheduleNew/report6.svg" alt="">
                <p class="reportNew-card-info-title">Сумма долгов</p>
            </div>
            <div class="reportNew-card-number">
                <?= number_format($invoicePendingSum, 0, ' ', ' ') ?> Сум
            </div>
        </a>
    </div>
    <form action="<?= Url::to(['cashier/report']) ?>">
        <div class="reportNew-select">
            <label class="reportNew-select-label">
                с
                <input type="date" name="start_date" value="<?= $data['start_date'] ?>">
            </label>
            <label class="reportNew-select-label">
                по
                <input type="date" name="end_date" value="<?= $data['end_date'] ?>">
            </label>
            <button type="submit" class="btn-reset btn-show">показать</button>
        </div>
    </form>

    <div class="patients">
        <div class="patients__table">
            <table cellspacing="0">
                <tr>
                    <td class="table_head">
                        <div class="filter_text">
                            <span>Инвойс</span>
                        </div>
                    </td>
                    <td class="table_head">
                        <div class="filter_text">
                            <span>Дата выставления</span>
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
                            <span>Дата оплаты</span>
                        </div>
                    </td>
                </tr>

                <?php
                foreach ($data['invoices'] as $invoice): ?>
                    <tr>
                        <td>
                            <p>#<?= $invoice->id ?></p>
                        </td>
                        <td>
                            <p><?= date('d.m.Y', strtotime($invoice->created_at)) ?></p>
                        </td>
                        <td>
                            <p><?= number_format($invoice->getInvoiceTotal(), 0, ' ', ' ') ?> сум</p>
                        </td>
                        <td>
                            <p>
                                <?= isset($invoice->transaction->amount)
                                    ? number_format($invoice->transaction->amount, 0, '', ' ') . ' сум'
                                    : null ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= isset($invoice->transaction->created_at)
                                    ? date('d.m.Y H:i', strtotime($invoice->transaction->created_at))
                                    : null ?>
                            </p>
                        </td>
                    </tr>
                <?php
                endforeach; ?>
            </table>
        </div>
        <?= $this->render('_pagination', ['data' => $data]) ?>
    </div>
</div>
