<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DoctorSchedule $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="doctor-schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_from')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'date_to')->textInput(['type' => 'date']) ?>
    <?= $form->field($model, 'doctor_id')->hiddenInput(['value' => isset($model->doctor_id) ? $model->doctor_id
        : Yii::$app->user->identity->id])->label(false); ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
