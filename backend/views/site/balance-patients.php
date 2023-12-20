<?php

/**@var array $data */

use yii\helpers\Url;

$this->title = 'Сумма авансовых денег';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="patients balance-patients">
    <h3 class="wrapper__box-title">Сумма авансовых денег</h3>
    <div class="balance-patients__table" style="height:calc(100% - 80px); overflow-y: auto">
        <table>
            <tr>
                <td>
                    <div class="filter_text">
                        <span>№</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>ФИО</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Аванс</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Дата добавления</span>
                    </div>
                </td>
            </tr>

            <?php if (empty($data['data'])): ?>
                <tr>
                    <td colspan="4">
                        <p class="text_center-table">Нет данных</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php $i = $data['offset'] + 1; foreach ($data['data'] as $key => $item): ?>
                    <tr>
                        <td>
                            <div class="filter_text">
                                <span><?= $i++ ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span>
                                    <a href="<?= Url::to([Yii::$app->user->can('cashier')
                                        ? 'patient/finance'
                                        : 'patient/update', 'id' => $item['patient_id']]) ?>">
                                        <?= $item['full_name'] ?>
                                    </a>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span><?= number_format($item['balance'], 0, ' ', ' ') ?> сум</span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span><?= date('d.m.Y H:i', strtotime($item['last_updated_date'])) ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>

    <?= $this->render('_pagination', ['data' => $data]) ?>
</div>
