<?php

/**@var Invoice $invoice */

/**@var Reception $visit */

use common\models\Invoice;
use common\models\Reception;

?>
<div class="edit-patients__table">
    <p>Записан на прием:</p>
    <table>
        <tr>
            <td>
                <div class="filter_text">
                    <span>#ID</span>
                </div>
            </td>
            <td>
                <div class="filter_text">
                    <span>Дата</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Время</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Врач</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Лечили</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Счета</span>
                </div>
            </td>
        </tr>

        <?php if (!empty($model->newVisits)): ?>
            <?php foreach ($model->newVisits as $visit): ?>
                <?php $invoice = $visit->getInvoice(); ?>
                <tr>
                    <td>
                        <p><?= $visit->id ?></p>
                    </td>
                    <td>
                        <p><?= $visit->record_date ?></p>
                    </td>
                    <td>
                        <p>с <?= $visit->record_time_from ?>
                            до <?= $visit->record_time_to ?></p>
                    </td>
                    <td>
                        <p>
                            <?= $visit->doctor->getFullName() ?>
                        </p>
                    </td>
                    <td>
                        <p>
                            <?php if ($invoice): ?>
                                <?= $invoice->allTeeth ?>
                            <?php endif; ?>
                        </p>
                    </td>
                    <td>
                        <p>
                            <?php if ($invoice): ?>
                                <span>№<?= $invoice->id ?></span> (<?= number_format(
                                    $invoice->invoiceTotal,
                                    0,
                                    ' ',
                                    ' '
                                ) ?> Сум)
                            <?php endif; ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Нет визитов</td>
            </tr>
        <?php endif; ?>
    </table>

    <p>История визитов:</p>
    <table>
        <tr>
            <td>
                <div class="filter_text">
                    <span>#ID</span>
                </div>
            </td>
            <td>
                <div class="filter_text">
                    <span>Дата</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Время</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Врач</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Лечили</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Счета</span>
                </div>
            </td>
        </tr>

        <?php if (!empty($model->oldVisits)): ?>
            <?php foreach ($model->oldVisits as $visit): ?>
                <?php $invoice = $visit->getInvoice(); ?>
                <tr>
                    <td>
                        <p><?= $visit->id ?></p>
                    </td>
                    <td>
                        <p><?= $visit->record_date ?></p>
                    </td>
                    <td>
                        <p>с <?= $visit->record_time_from ?>
                            до <?= $visit->record_time_to ?></p>
                    </td>
                    <td>
                        <p>
                            <?= $visit->doctor->getFullName() ?>
                        </p>
                    </td>
                    <td>
                        <p>
                            <?php if ($invoice): ?>
                                <?= $invoice->allTeeth ?>
                            <?php endif; ?>
                        </p>
                    </td>
                    <td>
                        <p>
                            <?php if ($invoice): ?>
                                <span>№<?= $invoice->id; ?></span> (<?= number_format(
                                    $invoice->invoiceTotal,
                                    0,
                                    ' ',
                                    ' '
                                ) ?> Сум)
                            <?php endif; ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Нет визитов</td>
            </tr>
        <?php endif; ?>
    </table>

    <p>Отмененный:</p>
    <table>
        <tr>
            <td>
                <div class="filter_text">
                    <span>#ID</span>
                </div>
            </td>
            <td>
                <div class="filter_text">
                    <span>Дата</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Время</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Врач</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Причина отказа</span>
                </div>
            </td>
            <td class="table_head">
                <div class="filter_text">
                    <span>Счета</span>
                </div>
            </td>
        </tr>

        <?php if (!empty($model->cancelledVisits)): ?>
            <?php foreach ($model->cancelledVisits as $visit): ?>
                <?php $invoice = $visit->getInvoice(); ?>
                <tr>
                    <td>
                        <p class="tex_grey"><?= $visit->id ?></p>
                    </td>
                    <td>
                        <p class="tex_grey"><?= $visit->record_date ?></p>
                    </td>
                    <td>
                        <p class="tex_grey">с <?= $visit->record_time_from ?>
                            до <?= $visit->record_time_to ?></p>
                    </td>
                    <td>
                        <p class="tex_grey">
                            <?= $visit->doctor->getFullName() ?>
                        </p>
                    </td>
                    <td>
                        <p class="tex_grey">
                            причина отказа: <?= $visit->cancel_reason ?>
                        </p>
                    </td>
                    <td>
                        <p class="tex_grey">
                            <?php if ($invoice): ?>
                                <span>№<?= $invoice->id ?></span> (<?= number_format(
                                    $invoice->invoiceTotal,
                                    0,
                                    ' ',
                                    ' '
                                ) ?> Сум)
                            <?php endif; ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Нет визитов</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
