<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DiscountRequest */

$this->title = 'Create Discount Request';
$this->params['breadcrumbs'][] = ['label' => 'Discount Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
