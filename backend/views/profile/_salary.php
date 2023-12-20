<?php

use common\models\User;
use yii\helpers\Url;

/**@var $data ArrayObject */
/**@var $model User */
/**@var $earnings User */
?>

<form action="<?= Url::to(['profile/index']) ?>" class="edit-employee-table-select">
    <label class="edit-employee-table-select-label">
        с
        <input type="date" name="start_date" value="<?= $data['start_date'] ?>">
    </label>
    <label class="edit-employee-table-select-label">
        по
        <input type="date" name="end_date" value="<?= $data['end_date'] ?>">
    </label>
    <button type="submit" class="btn-reset btn-show">показать</button>
</form>

<div class="edit-employee-table">
    <h3 class="edit-employee-table__title">Зарплата</h3>
    <!--  table -->
    <div class="edit-employee-table__table" style="height:calc(100% - 75px); overflow-y: auto">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Инвойс</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Пациент</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата приема</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Категория</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Услуга</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Заработок</span>
                    </div>
                </td>
            </tr>

            <?php if (!empty($earnings)): ?>
                <?php foreach ($earnings as $earning): ?>
                    <tr>
                        <td class="table__body-td">
                            <p>
                                #<?= $earning->invoice_id ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $earning->patient_name ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= date('d.m.Y H:i', strtotime($earning->visit_time)) ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $earning->cat_title ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>Вставление зубов</p>
                        </td>
                        <td class="table__body-td">
                            <p style="color: #003FDE">
                                <?= number_format($earning->employee_earnings, 0, ' ', ' ') ?> Сум
                            </p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
    <div class="edit-employee-table-bottom">
        <div class="edit-employee-table-bottom-right">
            <p class="edit-employee-table-bottom-title">ИТОГО</p>
            <span class="edit-employee-table-bottom-number">
                <?= number_format(User::getTotalEarnings($model->id, $data['start_date'], $data['end_date']), 0, ' ', ' '); ?> Сум
            </span>
        </div>
    </div>
</div>


