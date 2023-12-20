<?php

use common\models\DoctorPercent;
use common\models\PriceList;
use common\models\User;

$csrfTokenName = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
/* @var $model User */
/* @var $cat PriceList */
?>
<div class="sj-card">
    <input type="hidden" name="percent-user-id" value="<?= $model->id ?>"/>
    <h2>Зарплата</h2>
    <table class="sj-table">
        <tr>
            <th></th>
            <th>Процент</th>
        </tr>
        <?php
        foreach (PriceList::find()->all() as $cat): ?>
            <?php
            $item = DoctorPercent::findOne(['user_id' => $model->id, 'price_list_id' => $cat['id']]); ?>
            <tr data-cat-id="<?= $cat->id ?>" class="percent-category">
                <td><?= $cat->section ?></td>
                <td>
                    <div class="inline-form-wrap">
                        <input type="text" name="percent" value="<?= isset($item) ? $item->percent : '' ?>" autocomplete="off"/>
                    </div>
                </td>
            </tr>
        <?php
        endforeach; ?>
    </table>
    <button class="sj-btn sj-btn-info save-doctor-percent">Сохранить</button>
</div>
