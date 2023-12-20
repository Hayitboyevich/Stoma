<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DoctorSchedule $model */

$this->title = 'Update Doctor Schedule: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Doctor Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doctor-schedule-update p-4" >

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
