<?php

use common\models\User;

$csrfTokenName = Yii::$app->request->csrfParam;

$csrfToken = Yii::$app->request->csrfToken;
/* @var $model User */
?>
<div class="patient-modal-wrap">
    <div class="patient-modal-fixed">
        <h2>Новый пациент</h2>
        <?php
        if (isset($model) && !empty($model->id)): ?>
            <input type="hidden" name="patient_id" value="<?= $model->id; ?>"/>
        <?php
        endif; ?>
        <div class="inline-form-wrap">
            <label>Фамилия</label>
            <input value="<?= isset($model) ? $model->lastname : ''; ?>" autocomplete="off" name="last_name"
                   type="text"/>
        </div>
        <div class="inline-form-wrap" style="float: right">
            <label>Имя</label>
            <input value="<?= isset($model) ? $model->firstname : ''; ?>" autocomplete="off" name="first_name"
                   type="text"/>
        </div>
        <br/>
        <div class="inline-form-wrap">
            <label>Телефон</label>
            <input value="<?= isset($model) ? $model->phone : ''; ?>" autocomplete="off" name="phone" type="text"/>
        </div>
        <div class="inline-form-wrap" style="float: right;">
            <label>VIP? </label>
            <select name="vip">
                <option value="no" <?= isset($model) && $model->vip ? 'selected' : ''; ?>>Нет</option>
                <option value="vip" <?= isset($model) && $model->vip ? 'selected' : ''; ?>>Да</option>
            </select>
        </div>
        <div class="inline-form-wrap">
            <label>Дата рождения</label>
            <input autocomplete="off" name="dob" type="date" value="<?= isset($model) ? $model->dob : ''; ?>"/>
        </div>
        <div class="inline-form-wrap patient-doctor" style="float: right;">
            <label>Лечаший врач</label>
            <select class="select2-instance" name="doctor_id">
                <option></option>
                <?php
                $doctors = User::find()->where(['role' => 'doctor'])->all(); ?>
                <?php
                if (!empty($doctors)): ?>
                    <?php
                    foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor->id; ?>" <?= isset($model) && $doctor->id == $model->doctor_id ? 'selected' : ''; ?>><?= $doctor->lastname; ?> <?= $doctor->firstname; ?></option>
                    <?php
                    endforeach; ?>
                <?php
                endif; ?>
            </select>
        </div>
        <div class="inline-form-wrap">
            <label>Пол </label>
            <select name="gender">
                <option value=""></option>
                <option value="M" <?= isset($model) && $model->gender == 'M' ? 'selected' : ''; ?>>Муж.</option>
                <option value="F" <?= isset($model) && $model->gender == 'F' ? 'selected' : ''; ?>>Жен.</option>
            </select>
        </div>

        <br/>
        <br/>
        <button class="<?= isset($model) ? 'edit-patient-button' : 'new-patient-button'; ?>">Сохранить</button>
    </div>
</div>