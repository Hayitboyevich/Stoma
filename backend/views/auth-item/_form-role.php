<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'disabled' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'type')->hiddenInput(['value' => 1])->label(false) ?>

    <?= $form->field($model, 'description')->textInput() ?>


    <?php
    $permissions = $model->permissionsArray();
    $options = \yii\helpers\ArrayHelper::map(\common\models\AuthItem::find()->where(['type' => 2])->all(), 'name', 'description');
    $params = [
            'item' => function ($index, $label, $name, $checked, $value) use ($permissions) {
                    if(in_array($value,$permissions)){
                        $checked = 'checked';
                    }
                    return Html::checkbox($name, $checked, [
                        'value' => $value,
                        'id' => $value,
                        'label' => '<label for="'.$value.'">' . $label .' ('.$value.')'. '</label>',
                    ]);
                },
                'separator' => '<br />'
            ];
    ?>

    <?= $form->field($model, 'permissions')->checkboxList($options,$params); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
