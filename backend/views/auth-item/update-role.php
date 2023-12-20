<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = 'Update Role: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'roles', 'url' => ['role']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-item-update box box-primary">

    <div class="box-body p-4">

    <h1 class="mb-4 mt-0" ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-role', [
        'model' => $model,
    ]) ?>
    </div>
</div>
