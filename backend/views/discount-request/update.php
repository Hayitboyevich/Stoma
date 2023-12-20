<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DiscountRequest */

$this->title = 'Update Discount Request: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Discount Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="discount-request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
