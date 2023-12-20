<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\OldEmployeeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="old-employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'patronymic') ?>

    <?= $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'work_start_date') ?>

    <?php // echo $form->field($model, 'work_end_date') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'initials') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'phone_home') ?>

    <?php // echo $form->field($model, 'phone_work') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
