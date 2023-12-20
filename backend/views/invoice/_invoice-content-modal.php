<?php

/** @var $invoice Invoice */
/** @var $paymentsSum int */

use common\models\Invoice;

?>
<div class="body__item">
    <p class="text-gray">Пациент:</p>
    <p class="text-bold"><?= $invoice->patient ? $invoice->patient->getFullName() : '-' ?></p>
</div>
<div class="body__item">
    <p class="text-gray">Дата визита:</p>
    <p class="text-bold"><?= date('d.m.Y', strtotime($invoice->getVisit())) ?></p>
</div>
<div class="body__item">
    <p class="text-gray">Мобильный номер:</p>
    <p class="text-bold"><?= $invoice->patient ? $invoice->patient->phone : '-' ?></p>
</div>
<div class="body__item">
    <p class="text-gray">Сумма инвойса:</p>
    <p class="text-bold"><?= number_format($invoice->getInvoiceTotal(), 0, ' ', ' ') ?> сум</p>
</div>
<div class="body__item">
    <p class="text-gray">Оплачено:</p>
    <p class="text-bold"><?= number_format($paymentsSum, 0, ' ', ' ') ?> сум</p>
</div>
<hr>
<div class="body__item">
    <p class="text-gray">Осталось:</p>
    <p class="text-bold">
        <?= number_format($invoice->getInvoiceTotal() - $paymentsSum, 0, ' ', ' ') ?> сум
    </p>
</div>
<label for="" class="body__label">
    Сумма инвойса
    <input type="number" name="amount" placeholder="Введите сумму">
</label>
<input type="hidden" name="invoice_id" value="<?= $invoice->id ?>">
