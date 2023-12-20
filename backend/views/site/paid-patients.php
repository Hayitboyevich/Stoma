<?php

/** @var Invoice $invoices */

use common\models\Invoice;
use yii\helpers\Url;

$this->title = 'Список оплаченных пациентов';

?>
<div class="patients">
    <div style="display: flex; justify-content: space-between">
        <div>
            <h2>Список оплаченных пациентов</h2>
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
                        <span>Дата визита</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата оплаты</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Сумма оплаты</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td>
                        <p><?= $invoice['invoice_id'] ?></p>
                    </td>
                    <td>
                        <a href="<?= Url::to(['patient/update', 'id' => $invoice['patient']['id']]) ?>">
                            <?= $invoice['patient']['p_full_name'] ?>
                        </a>
                    </td>
                    <td>
                        <?= $invoice['doctor']['d_full_name'] ?>
                    </td>
                    <td>
                        <?= date('d.m.Y H:i', strtotime($invoice['visit_time'])) ?>
                    </td>
                    <td>
                        <?= date('d.m.Y H:i', strtotime($invoice['created_at'])) ?>
                    </td>
                    <td>
                        <?= number_format($invoice['paid_sum'], 0, '', ' ') ?> сум
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
