<?php

/* @var $patients Patient */
/* @var $model Patient */

use common\models\Patient;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<div class="transfer-money-modal" style="z-index: 999 !important;">
    <div class="transfer-money-form-wrap">
        <div class="transfer-money-container">
            <div class="transfer-money-form-wrap-header">
                <h2 class="transfer-money-form-wrap-header-title">Перевод денег</h2>
                <img src="/img/scheduleNew/IconClose.svg" alt="close" class="transfer-money-form-wrap-header-close">
            </div>
            <div class="transfer-money-form-wrap-body">
                <div class="body-select">
                    <div class="body-select-item">
                        <label class="body-select-item-title" for="sender-patient-id">Отправитель пациент</label>
                        <?= Select2::widget([
                            'name' => 'patient_id',
                            'data' => ArrayHelper::map($patients, 'id', 'name'),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'value' => $model->id,
                            'options' => [
                                'placeholder' => 'Выбрать пациент',
                                'id' => 'sender-patient-id',
                                'style' => 'width: 100%;',
                            ],
                        ]) ?>
                        <div class="body-select-item-price">
                            <p class="body-select-item-price-text">баланс</p>
                            <span class="body-select-item-text">Баланс: <?= number_format($model->getPrepayment(), 0, '', ' ') ?> сум</span>
                        </div>

                    </div>
                    <div class="body-select-item">
                        <label class="body-select-item-title" for="recipient-patient-id">
                            Получатель пациент
                        </label>
                        <?= Select2::widget([
                            'name' => 'patient_id',
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'data' => ArrayHelper::map($patients, 'id', 'name'),
                            'options' => [
                                'placeholder' => 'Выбрать пациент',
                                'id' => 'recipient-patient-id'
                            ]
                        ]) ?>

                        <div class="body-select-item-price">
                            <p class="body-select-item-price-text">баланс</p>
                            <span class="body-select-item-text">Баланс: 0 сум</span>
                        </div>
                    </div>
                </div>
                <div class="body-select-item-price">
                    <p class="body-select-item-price-text">Сумма</p>
                    <input class="body-select-item-price-input" type="number" placeholder="Введите сумму"
                           name="amount"/>
                </div>
            </div>
            <div class="transfer-money-form-wrap-footer">
                <button class="transfer-money-btn btn-reset btn-blue">
                    Перевод
                </button>
            </div>
        </div>
    </div>
</div>


