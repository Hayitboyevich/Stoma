<?php

/**@var $model common\models\Invoice */

use common\models\Invoice;

?>

<div class="print">
    <div class="print__header">
        <div>
            <img src="/img/logoIcon.svg" style="width: 40px;"/>
            <img src="/img/logo.svg" style="width: 250px;"/>
        </div>
        <div><img src="/img/svg/kidsmile.svg" style="width: 200px; height: auto;" alt=""></div>
    </div>
    <div class="print__top">
        <div>
            <p class="print__info">OOO «Stomaservice»</p>
            <p class="print__info">ОКЭД: 86230</p>
            <p class="print__info">100187,</p>
            <p class="print__info">г.Ташкент М.Улугбекский р-н</p>
            <p class="print__info">ул.Буюк Ипак Йули, 235</p>
            <br>
            <p class="print__info">Дата: <?= date('d.m.Y H:i', strtotime($model->created_at)) ?></p>
        </div>
        <div>
            <p class="print__info bold">Приложение №2 к Договору №____от «___202__года</p>
            <p class="print__info bold">АКТ ОБ ОКАЗАНИИ УСЛУГ от «  »__________20__г</p>
            <p class="print__info bold">202__ йил «даги-сонли Шартнома 2-Илова</p>
            <p class="print__info bold">КУРСАТИЛГАН ХИЗМАТЛАР<br>ТУГРИСИДА ДАЛОЛАТНОМА 202__ йил “____</p>
            <p class="print__info bold">Appendix №2 to Agreement No. ______dated ____________, 202__</p>
            <p class="print__info bold">CERTIFICATE OF RENDERED SERVICES Dated _________, 20__</p>
        </div>
    </div>

    <?php if ($model->preliminary === Invoice::PRELIMINARY): ?>
        <h3 style="color: red; text-align: center">ПРЕДВАРИТЕЛЬНЫЙ СЧЕТ</h3>
    <?php endif; ?>

    <div class="print__user">
        <p class="print__user-title">Врач: <span><?= $model->doctor->getFullName() ?></span></p>
        <p class="print__user-title">Пациент: <span><?= $model->patient->getFullName() ?></span></p>
        <?php if ($model->discount > 0): ?>
            <p class="print__user-title">
                Скидка: <span><?= $model->discount ?>%</span>
            </p>
        <?php endif; ?>
    </div>
    <table class="print__table" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th>Наименование позиции</th>
            <th>Зубы</th>
            <th>Количество</th>
            <th>Цена</th>
            <?php if ($model->discount > 0): ?>
                <th>Цена со скидкой</th>
            <?php endif; ?>
            <th>Итого сумма</th>
        </tr>
        </thead>
        <tbody>
            <?php if (!empty($model->invoiceServices)): ?>
                <?php foreach ($model->invoiceServices as $invoiceService): ?>
                    <tr>
                        <td><?= $invoiceService->priceListItem->name ?? '' ?></td>
                        <td><?= $invoiceService->teeth ?></td>
                        <td><?= $invoiceService->amount ?></td>
                        <td><?= number_format($invoiceService->price, 0, '', ' ') ?> сўм</td>
                        <?php if ($model->discount > 0): ?>
                            <td><?= number_format($invoiceService->price_with_discount, 0, '', ' ') ?> сўм</td>
                        <?php endif; ?>
                        <td><?= number_format($invoiceService->price_with_discount * $invoiceService->amount * $invoiceService->teeth_amount, 0, '', ' ') ?>
                            сўм
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>
        </tbody>
    </table>
    <h3 class="print__title">Итого:</h3>
    <div class="print__price">
        <div>
            <p class="print__price-text">
                Сумма Счета: <span><?= number_format($model->getInvoiceTotalWithoutDiscount(), 0, '', ' ') ?> сўм</span>
            </p>
            <?php if ($model->discount > 0): ?>
                <p class="print__price-text">
                    Сумма счета со скидкой: <span><?= number_format($model->getInvoiceTotal(), 0, '', ' ') ?> сўм</span>
                </p>
            <?php endif; ?>
            <p class="print__price-text">
                Оплачено: <span><?= $model->getPayments()->sum('amount') > 0
                        ? number_format($model->getPayments()->sum('amount'), 0, '', ' ')
                        : 0 ?> сўм</span>
            </p>
            <p class="print__price-text">
                Долг: <span><?= number_format($model->getRemains(), 0, '', ' ') ?> сўм</span>
            </p>
        </div>
        <div>
            <p class="print__price-text" style="justify-content: space-between">
                Общий долг: <span><?= number_format($model->patient->getDebt(), 0, '', ' ') ?> сўм</span>
            </p>
            <p class="print__price-text" style="justify-content: space-between">
                Сумма на авансе: <span><?= number_format($model->patient->getPrepayment(), 0, '', ' ') ?> сўм</span>
            </p>
        </div>
    </div>
    <h3 class="print__title" style="margin-bottom: 6px">История платежей по счету</h3>
    <table class="print__table-price" cellpadding="0" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
            <?php if (!empty($model->payments)): ?>
                <?php foreach ($model->payments as $payment): ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($payment->created_at)) ?></td>
                        <td><?= number_format($payment->amount, 0, '', ' ') ?> сўм</td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center">Платежи отсутствуют</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="print__price" style="margin-top: 30px;">
        <div>
            <p class="print__user-title">Врач: <span><?= $model->doctor->getFullName() ?></span></p>
        </div>
        <div>
            <p class="print__price-text">Пациент: <span>_______________</span></p>
            <p class="print__price-text">Законный представитель: <span>_______________</span></p>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();
</script>
<style media="print">
    @page {
        margin: 20px;  /* this affects the margin in the printer settings */
    }
</style>
<style>
    body {
        font-family: Roboto;
    }

    .print {
        max-width: 100%
    }

    .print__header {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-bottom: 14px
    }

    .print__top {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-bottom: 30px
    }

    .print__info {
        font-weight: 400;
        font-size: 12px;
        line-height: 14px;
        text-align: left;
        color: #2e303a;
        margin: 0
    }

    .print__info:not(:last-child) {
        margin-bottom: 4px
    }

    .print .bold {
        font-weight: 600
    }

    .print__user {
        margin-bottom: 13px
    }

    .print__user-title {
        margin: 0;
        font-weight: 400;
        font-size: 14px;
        line-height: 16px;
        color: #2e303a
    }

    .print__user-title span {
        font-weight: 600;
        font-size: 16px;
        line-height: 19px;
        color: #2e303a
    }

    .print__table {
        border: 1px solid black;
        border-radius: 10px;
        margin-bottom: 12px;
        width: 100%;
    }

    .print__table thead {
        border-bottom: 1px solid black;
    }

    .print__table thead tr th {
        font-weight: 400;
        font-size: 12px;
        line-height: 14px;
        color: #001b60;
        text-align: left;
        padding: 10px;
        border-bottom: 1px solid black;
    }

    .print__table tbody tr td {
        font-weight: 600;
        font-size: 12px;
        line-height: 14px;
        color: #00154a;
        text-align: left;
        padding: 10px
    }

    .print__table tbody tr td:not(:last-child) {
        border-right: 1px solid black;
    }

    .print__title {
        margin: 0 0 10px;
        font-weight: 600;
        font-size: 18px;
        line-height: 21px;
        color: #2e303a
    }

    .print__price {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-bottom: 20px
    }

    .print__price-text {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-column-gap: 4px;
        -moz-column-gap: 4px;
        column-gap: 4px;
        margin: 0;
        font-weight: 400;
        font-size: 14px;
        line-height: 16px;
        color: #2e303a
    }

    .print__price-text span {
        font-weight: 600;
        font-size: 16px;
        line-height: 19px;
        color: #2e303a
    }

    .print__price-text:not(:last-child) {
        margin-bottom: 8px
    }

    .print__table-price {
        border: 1px solid black;
        border-radius: 10px;
        margin-bottom: 12px
    }

    .print__table-price thead {
        border-bottom: 1px solid black;
    }

    .print__table-price thead tr th {
        font-weight: 400;
        font-size: 12px;
        line-height: 14px;
        color: #001b60;
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid black;
    }

    .print__table-price tbody tr td {
        font-weight: 600;
        font-size: 12px;
        line-height: 14px;
        color: #00154a;
        text-align: center;
        padding: 10px
    }

    .print__table-price tbody tr td:not(:last-child) {
        border-right: 1px solid black;
    }
</style>

