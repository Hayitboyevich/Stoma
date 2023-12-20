<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PriceListItem */

$this->title = 'Изменить услугу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Price List Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="price-list-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
