<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\OldEmployee $model */

$this->title = 'Update Old Employee: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Old Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="old-employee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
