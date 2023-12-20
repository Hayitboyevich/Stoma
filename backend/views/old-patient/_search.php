<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\OldPatientSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="old-patient-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'card_number') ?>

    <?= $form->field($model, 'first_visit') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'first_name') ?>

    <?php // echo $form->field($model, 'patronymic') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'phone_home') ?>

    <?php // echo $form->field($model, 'phone_work') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'home_address') ?>

    <?php // echo $form->field($model, 'doctor_id') ?>

    <?php // echo $form->field($model, 'hygienist_id') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'recommended_patient') ?>

    <?php // echo $form->field($model, 'recommended_user') ?>

    <?php // echo $form->field($model, 'who_were_recommended') ?>

    <?php // echo $form->field($model, 'patient_status') ?>

    <?php // echo $form->field($model, 'debt') ?>

    <?php // echo $form->field($model, 'credit') ?>

    <?php // echo $form->field($model, 'recommendations_amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
