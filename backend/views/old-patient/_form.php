<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\OldPatient $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="old-patient-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'card_number')->textInput() ?>

    <?= $form->field($model, 'first_visit')->textInput() ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dob')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_home')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_work')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'home_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doctor_id')->textInput() ?>

    <?= $form->field($model, 'hygienist_id')->textInput() ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recommended_patient')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recommended_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'who_were_recommended')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patient_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'debt')->textInput() ?>

    <?= $form->field($model, 'credit')->textInput() ?>

    <?= $form->field($model, 'recommendations_amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
