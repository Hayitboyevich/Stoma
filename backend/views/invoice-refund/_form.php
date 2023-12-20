<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\InvoiceRefund $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="invoice-refund-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoice_id')->textInput() ?>

    <?= $form->field($model, 'requested_user_id')->textInput() ?>

    <?= $form->field($model, 'approved_or_declined_user_id')->textInput() ?>

    <?= $form->field($model, 'approved_or_declined_at')->textInput() ?>

    <?= $form->field($model, 'approved_or_declined_comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comments')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
