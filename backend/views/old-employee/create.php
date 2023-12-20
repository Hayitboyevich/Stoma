<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\OldEmployee $model */

$this->title = 'Create Old Employee';
$this->params['breadcrumbs'][] = ['label' => 'Old Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="old-employee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
