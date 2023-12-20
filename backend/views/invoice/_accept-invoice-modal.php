<?php

/** @var $model Patient */
/** @var $invoice Invoice */

use common\models\Invoice;
use common\models\Patient;

?>

<div class="accept-invoice-modal">
    <div class="accept-invoice-form-wrap">
        <div class="invoice__modal">
            <div class="invoice__modal-header">
                <h3 class="invoice__modal-header-title">Информация о инвойсе</h3>
                <img src="/img/scheduleNew/IconClose.svg" onclick="closeModal('.accept-invoice-modal')" alt="">
            </div>
            <div class="invoice__modal-body">

            </div>
            <div class="invoice__modal-footer">
                <button class="btn-reset btn-blue pay-insurance-invoice-btn">Оплатить</button>
            </div>
        </div>
    </div>
</div>
