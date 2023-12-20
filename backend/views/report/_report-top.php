
<?php use common\models\User; ?>
<?php /** @var array $params */ ?>

<div class="report__top">
    <div class="top_select">
        <div class="select_wrapper">
            <label>Отчет</label>
            <select class="select2-instance" id="report-top-select">
                <option value="daily" <?= $params['action'] == 'daily' ? 'selected' : ''; ?>>Дневной отчет</option>
                <option value="period-report" <?= $params['action'] == 'period-report' ? 'selected' : ''; ?>>Отчет за период</option>
                <option value="salary" <?= $params['action'] == 'salary' ? 'selected' : ''; ?>>Зарплата</option>
                <option value="invoices" <?= $params['action'] == 'invoices' ? 'selected' : ''; ?>>Выставленные счета</option>
                <option value="debit-credit" <?= $params['action'] == 'debit-credit' ? 'selected' : ''; ?>>Долги и авансы</option>
                <!--<option value="dynamics" <?/*= $params['action'] == 'dynamics' ? 'selected' : ''; */?>>Динамика</option>-->
            </select>
        </div>
    </div>
    <?php if($params['action'] == 'invoices'): ?>
        <div class="top_select">
            <div class="select_wrapper">
                <label>Врач</label>
                <select class="select2-instance" name="doctor_id">
                    <option></option>
                    <?php

                    $doctors = User::find()->all(); ?>
                    <?php if(!empty($doctors)): ?>
                        <?php foreach ($doctors AS $doctor): ?>
                            <option value="<?= $doctor->id; ?>" <?= isset($model) && $doctor->id == $model->doctor_id ? 'selected' : '';  ?>><?= $doctor->lastname; ?> <?= $doctor->firstname; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
</div>