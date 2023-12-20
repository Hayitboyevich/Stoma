<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DoctorItemPercent $model */

$this->title = 'Update Doctor Item Percent: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Doctor Item Percents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doctor-item-percent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
