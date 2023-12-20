<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DoctorSchedule $model */

$this->title = 'Create Doctor Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Doctor Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-schedule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
