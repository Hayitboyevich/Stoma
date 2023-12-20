<?php

/** @var Transaction $transactions */

use common\models\Transaction;
use yii\helpers\Url;

$this->title = 'Переводы'

?>
<div class="patients">
    <h3 class="wrapper__box-title">Переводы</h3>
    <div class="patients__table" style="overflow-y: auto">
        <table>
            <tr>
                <td>
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Отправитель пациент</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Приемный пациент</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Сумма</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Пользователь</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td>
                        <p><?= $transaction->id ?></p>
                    </td>
                    <td>
                        <?= $transaction->patient
                            ? "<a href='" . Url::to([Yii::$app->user->can('cashier') ? 'patient/finance' : 'patient/update', 'id' => $transaction->patient->id]) . "'>" . $transaction->patient->getFullName() . "</a>"
                            : '-' ?>
                    </td>
                    <td>
                        <?= $transaction->receptionPatient
                            ? "<a href='" . Url::to([Yii::$app->user->can('cashier') ? 'patient/finance' : 'patient/update', 'id' => $transaction->receptionPatient->id]) . "'>" . $transaction->receptionPatient->getFullName() . "</a>"
                            : '-' ?>
                    </td>
                    <td>
                        <span style="color: <?= $transaction->type === Transaction::TYPE_PAY ? 'red' : '#28a745' ?>">
                            <?= $transaction->type === Transaction::TYPE_PAY ? '-' : '+' ?><?= number_format(
                                $transaction->amount,
                                0,
                                ' ',
                                ' '
                            ) ?> сум
                        </span>
                    </td>
                    <td>
                        <p><?= $transaction->user ? $transaction->user->getFullName() : '-' ?></p>
                    </td>
                    <td>
                        <p><?= date('d.m.Y H:i', strtotime($transaction->created_at)) ?></p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
