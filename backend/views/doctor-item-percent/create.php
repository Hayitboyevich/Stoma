<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DoctorItemPercent $model */

$this->title = 'Create Doctor Item Percent';
$this->params['breadcrumbs'][] = ['label' => 'Doctor Item Percents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-item-percent-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
