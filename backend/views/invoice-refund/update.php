<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\InvoiceRefund $model */

$this->title = 'Update Invoice Refund: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Refunds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-refund-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
