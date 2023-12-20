<?php

use common\models\PatientExamination;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $patientExamination PatientExamination */

$this->title = 'Новый осмотр';

?>
<div class="patient-examination">

    <?php $form = ActiveForm::begin(); ?>

    <div class="new-examination-form-wrap">
        <h1>Данные осмотра</h1>

        <hr>

        <?= $form->field($patientExamination, 'patient_id')->hiddenInput(['value' => isset($patientId) ? $patientId : 0])->label(false) ?>

        <div class="inline-form-wrap">
            <?= $form->field($patientExamination, 'complaints')->textInput() ?>
        </div>
        <div class="inline-form-wrap">
            <?= $form->field($patientExamination, 'anamnesis')->textInput() ?>
        </div>

        <div class="inline-form-wrap">
            <?= $form->field($patientExamination, 'weight')->textInput() ?>
        </div>
        <div class="inline-form-wrap">
            <?= $form->field($patientExamination, 'height')->textInput() ?>
        </div>

        <div class="inline-form-wrap">
            <?= $form->field($patientExamination, 'head_circumference')->textInput() ?>
        </div>
        <div class="inline-form-wrap">
            <?= $form->field($patientExamination, 'temperature')->textInput() ?>
        </div>

        <div class="inline-form-wrap" style="width: 100%; margin-bottom: 10px;">
            <?= $form->field($patientExamination, 'diagnosis')->textInput() ?>
        </div>

        <div class="recommendation-text-redactor">
            <?= $form->field($patientExamination, 'inspection_data')->widget(TinyMce::className(), [
                'options' => ['rows' => 6],
                'language' => 'ru',
                'clientOptions' => [
                    'menubar' => false,
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
                ],
            ]) ?>
        </div>

        <div class="recommendation-text-redactor">
            <?= $form->field($patientExamination, 'recommendations')->widget(TinyMce::className(), [
                'options' => ['rows' => 6],
                'language' => 'ru',
                'clientOptions' => [
                    'menubar' => false,
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
                ],
            ]) ?>
        </div>

        <div class="inline-form-wrap">&nbsp;</div>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'sj-btn sj-btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
