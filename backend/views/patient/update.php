<?php

/* @var $model Patient */
/* @var $invoice Invoice */
/* @var $record Reception */
/* @var $priceLists PriceList */
/* @var $assistants User */
/* @var $doctors User */

use common\models\Invoice;
use common\models\Patient;
use common\models\PriceList;
use common\models\Reception;
use common\models\User;

$this->title = $model->getFullName();

?>

<div class="edit-patients">
    <div class="edit-patients__top">
        <div class="edit-patients__top_left">
            <ul>
                <li><a href="/patient/index">Пациенты</a></li>
                <li><a href="javascript:void(0)">»</a></li>
                <li class="active_link"><a href=""><?= $model->getFullName() ?></a></li>
            </ul>
        </div>
    </div>

    <?= $this->render('_patient-info', [
        'invoice' => $record,
        'model' => $model
    ]) ?>

    <div class="edit-patients__history">
        <div class="history_tabs">
            <div class="history_text <?= $record ? '' : 'active' ?>" data-tab-name="tab-1">
                <p>Визиты</p>
            </div>

            <div class="history_text <?= $record ? 'active' : '' ?>" data-tab-name="tab-2">
                <p>Счета</p>
            </div>
            <?php if (Yii::$app->user->can('upload_patient_files')): ?>
                <div class="history_text" data-tab-name="tab-files">
                    <p>Файлы</p>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('new_examination_create')): ?>
                <div class="history_text" data-tab-name="tab-examinations">
                    <p>Осмотры</p>
                </div>
            <?php endif; ?>
        </div>
        <!--  table -->
        <div class="tab-1 tab <?= $record ? '' : 'is-active' ?>">
            <?= $this->render('_visits', [
                'model' => $model
            ]) ?>
        </div>

        <div class="tab-2 tab <?= $record ? 'is-active' : '' ?>">
            <!--      patients__none-->
            <?= $this->render('_bill', [
                'model' => $model,
                'invoice' => $invoice,
                'record' => $record,
                'doctors' => $doctors,
                'assistants' => $assistants
            ]) ?>
        </div>
        <?php if (Yii::$app->user->can('upload_patient_files')): ?>
            <div class="tab-files tab">
                <?= $this->render('_files', [
                    'model' => $model,
                    'invoice' => $invoice,
                    'record' => $record
                ]) ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('new_examination_create')): ?>
            <div class="tab-examinations tab">
                <?= $this->render('_examinations', [
                    'model' => $model,
                    'invoice' => $invoice,
                    'record' => $record
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <?= $this->render('_price', [
        'priceLists' => $priceLists,
    ]) ?>

</div>

<?= $this->render('_new-patient-modal', [
    'model' => $model
]) ?>

<?= $this->render('_assign-discount-modal', [
    'model' => $model
]) ?>

<?= $this->render('_invoice-pay') ?>
