<?php

use common\models\Invoice;
use common\models\Transaction;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Transaction */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var yii\web\View $this */
/* @var array $data */

$this->title = 'Статистика';

?>

<div class="patients cashier">
    <h3 class="wrapper__box-title">Кассир</h3>
    <div class="cashier__table" style="height: calc(100vh - 215px);overflow-y: auto">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="filter_text">
                        <span>Тип оплаты</span>
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
                        <span>Тип</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата</span>
                    </div>
                </td>
            </tr>
            <?php if (empty($data['transactions'])): ?>
                <tr>
                    <td colspan="5">
                        <p class="history__log_info text_center-table">Нет данных</p>
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($data['transactions'] as $transaction): ?>
                <tr>
                    <td>
                        <p class="cashier_info"><?= Transaction::PAYMENT_METHODS[$transaction->payment_method] ?? '-' ?></p>
                    </td>
                    <td>
                        <p class="cashier_info"><?= $transaction->patient ? $transaction->patient->getFullName() : '-' ?></p>
                    </td>
                    <td>
                        <p class="cashier_info"><?= number_format($transaction->amount, 0, '', ' ') ?> сум</p>
                    </td>
                    <td>
                        <p class="cashier_info"><?= Transaction::TYPE[$transaction->type] ?? '-' ?></p>
                    </td>
                    <td>
                        <p class="cashier_info"><?= date('d.m.Y H:i', strtotime($transaction->created_at)) ?></p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?= $this->render('_pagination-stats.php', ['data' => $data]) ?>
</div>