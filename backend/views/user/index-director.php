<?php

use common\models\PriceList;
use common\models\User;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $users array */
/* @var $priceLists PriceList */

$this->title = 'Список сотрудников для учред';

?>

<div class="employees-list">
    <p class="title">Список сотрудников для учред</p>
    <div class='table_wrapper-top' >
        <div class='filter_wrapper'>
            <?php
            $sortDirection = Yii::$app->request->get('sort', SORT_ASC);
            if ($sortDirection == SORT_ASC) {
                $sortDirection = SORT_DESC;
            } else {
                $sortDirection = SORT_ASC;
            }
            ?>
            <a href="<?= Url::current(['sort' => $sortDirection]) ?>">
                <button class="filter_btn">
                    <img src="/img/icon-filter.svg" alt="icon-filter">
                </button>
            </a>
            <form action="<?= Url::to(['user/index']) ?>" method="get">
            <div class='input_wrapper'>
                <img src="/img/icon-search.svg" alt="icon-search">
                <input type="text" name="text">
            </div>
            </form>
        </div>
        <button class="add-btn new-doctor-btn">
            Добавить доктора
            <img src="/img/IconAddWhite.svg" alt="IconAddWhite">
        </button>
    </div>
    <div class="table_wrapper">
        <table>
            <thead>
            <tr>
                <th>№</th>
                <th>ФИО</th>
                <th>Роль</th>
                <?php if (!empty($priceLists)): ?>
                    <?php foreach ($priceLists as $priceList): ?>
                        <th><?= $priceList->section ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
                <th>Статус работы</th>
            </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td>
                                <a href="<?= Url::to(['user/update', 'id' => $user['id']]) ?>">
                                    <?= $user['fullName'] ?>
                                </a>
                            </td>
                            <td><?= User::USER_ROLE[$user['role']] ?? '-' ?></td>
                            <?php if (!empty($user['doctor_percent'])): ?>
                                <?php foreach ($user['doctor_percent'] as $percent): ?>
                                    <td><?= $percent ?></td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <td>
                                <p class="status <?= $user['work_status'] == User::WORK_STATUS_AVAILABLE ? 'green' : 'red'; ?>">
                                    <?= User::WORK_STATUS[$user['work_status']] ?>
                                </p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->render('_new-doctor-modal') ?>