<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DoctorItemPercent $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="doctor-item-percent-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'doctor_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(
            \common\models\User::find()->where(['role' => 'doctor'])->all(),
            'id',
            'firstname'
        ),
        ['prompt' => 'Select Doctor']
    ) ?>

    <?= $form->field($model, 'price_list_item_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(
            \common\models\PriceListItem::find()->all(),
            'id',
            'name'
        ),
        ['prompt' => 'Select Price List Item']
    ) ?>

    <?= $form->field($model, 'percent')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
