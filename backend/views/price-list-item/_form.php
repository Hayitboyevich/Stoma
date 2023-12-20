<?php

use common\models\PriceList;
use common\models\PriceListItem;
use common\models\TechnicianPriceList;
use dosamigos\switchinput\SwitchBox;
use kartik\switchinput\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PriceListItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="price-list-item-form">

    <?php
    $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'price_list_id')
                ->dropDownList(
                    ArrayHelper::map(
                        PriceList::find()->all(),
                        'id',
                        'section'
                    )
                ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'utilities', [
                'template' => '{label}<div class="input-group mb-2 mr-sm-2">
                        {input}
                    <div class="input-group-prepend">
                      <div class="input-group-text">'
                    . PriceListItem::DEFAULT_PERCENT . '%</div>
                    </div>
                    {error}
                  </div>',
            ])->textInput(
                [
                    'disabled' => true,
                    'data-percent' => PriceListItem::DEFAULT_PERCENT,
                    'value' => !$model->isNewRecord ? $model->price
                        * $model->utilities
                        / 100 : 0,
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'marketing', [
                'template' => '{label}<div class="input-group mb-2 mr-sm-2">
                        {input}
                    <div class="input-group-prepend">
                      <div class="input-group-text">'
                    . PriceListItem::MARKETING_PERCENT . '%</div>
                    </div>
                    {error}
                  </div>',
            ])->textInput(
                [
                    'disabled' => true,
                    'data-percent' => PriceListItem::MARKETING_PERCENT,
                    'value' => !$model->isNewRecord ? $model->price
                        * $model->marketing
                        / 100 : 0,
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'price')->textInput(['type' => 'number']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'amortization', [
                'template' => '{label}<div class="input-group mb-2 mr-sm-2">
                        {input}
                    <div class="input-group-prepend">
                      <div class="input-group-text">'
                    . PriceListItem::AMORTIZATION_PERCENT . '%</div>
                    </div>
                    {error}
                  </div>',
            ])->textInput(
                [
                    'disabled' => true,
                    'data-percent' => PriceListItem::AMORTIZATION_PERCENT,
                    'value' => !$model->isNewRecord ? $model->price
                        * $model->amortization
                        / 100 : 0,
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'consumable')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'other_expenses', [
                'template' => '{label}<div class="input-group mb-2 mr-sm-2">
                        {input}
                    <div class="input-group-prepend">
                      <div class="input-group-text">'
                    . PriceListItem::OTHER_EXPENSES_PERCENT . '%</div>
                    </div>
                    {error}
                  </div>',
            ])->textInput(
                [
                    'disabled' => true,
                    'data-percent' => PriceListItem::OTHER_EXPENSES_PERCENT,
                    'value' => !$model->isNewRecord ? $model->price
                        * $model->other_expenses
                        / 100 : 0,
                ]
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'technician_price_list_id')
                ->dropDownList(
                    ArrayHelper::map(
                        TechnicianPriceList::find()
                            ->where(['status' => TechnicianPriceList::STATUS_ACTIVE])
                            ->all(),
                        'id',
                        'name'
                    ),
                    ['prompt' => 'Выберите услуга техника']
                ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'discount_apply')->radioList(
                [
                    1 => 'Да',
                    0 => 'Нет'
                ],
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success float-right']) ?>
    </div>

    <?php
    ActiveForm::end(); ?>

</div>
