<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\OldPatient $model */

$this->title = 'Update Old Patient: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Old Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="old-patient-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
