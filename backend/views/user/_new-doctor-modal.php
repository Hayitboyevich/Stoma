<?php

use common\models\PriceList;
use common\models\User;
use yii\helpers\ArrayHelper;

$csrfTokenName = Yii::$app->request->csrfParam;

$csrfToken = Yii::$app->request->csrfToken;
/* @var $model User */
?>
<div class="doctor-modal-wrap">
    <div class="doctor-modal-fixed">
        <input type="hidden" name="<?= $csrfTokenName; ?>" value="<?= $csrfToken; ?>"/>
        <?php
        if (isset($model) && !empty($model->id)): ?>
            <input type="hidden" name="user_id" value="<?= $model->id; ?>"/>
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
            <label>Email</label>
            <input value="<?= isset($model) ? $model->email : ''; ?>" autocomplete="off" name="email" type="text"/>
        </div>
        <div class="inline-form-wrap">
            <label>Дата начала работы</label>
            <input autocomplete="off" name="work_start_date" type="date"
                   value="<?= isset($model) ? $model->work_start_date : ''; ?>"/>
        </div>
        <div class="inline-form-wrap" style="float: right;">
            <label>Дата рождения</label>
            <input autocomplete="off" name="dob" type="date" value="<?= isset($model) ? $model->dob : ''; ?>"/>
        </div>

        <br/>
        <div class="inline-form-wrap">
            <label>Пароль</label>
            <input autocomplete="off" name="password" type="password"/>
        </div>
        <div class="inline-form-wrap" style="float:right;">
            <label>повторить Пароль</label>
            <input autocomplete="off" name="password2" type="password"/>
        </div>
        <br/>
        <div class="inline-form-wrap">
            <label>Роль </label>
            <select name="role">
                <?php
                foreach (User::USER_ROLE as $index => $role): ?>
                    <option value="<?= $index ?>" <?= isset($model) && $model->role == $index ? 'selected' : '' ?>>
                        <?= $role ?>
                    </option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="inline-form-wrap doctor-sections" style="float: right;">
            <label>Отделения </label>
            <?php
            $selected_cats = [];
            if (isset($model) && !empty($model->categories)) {
                $selected_cats = ArrayHelper::getColumn($model->categories, 'id');
            }
            ?>
            <select class="select2-instance" multiple name="categories[]">
                <option></option>
                <?php
                foreach (PriceList::find()->all() as $item): ?>
                    <option value="<?= $item->id ?>" <?= in_array(
                        $item->id,
                        $selected_cats
                    ) ? 'selected' : '' ?>><?= $item->section ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="inline-form-wrap">
            <label>Работает? </label>
            <select name="work_status">
                <option value="available" <?= isset($model) && $model->work_status == 'available' ? 'selected' : ''; ?>>
                    Да
                </option>
                <option value="vacation" <?= isset($model) && $model->work_status == 'vacation' ? 'selected' : ''; ?>>В
                    отпуске
                </option>
            </select>
        </div>
        <br/>
        <br/>
        <button class="<?= isset($model) ? 'edit-doctor-button' : 'new-doctor-button' ?>">Сохранить</button>
    </div>
</div>
