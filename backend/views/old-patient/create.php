<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\OldPatient $model */

$this->title = 'Create Old Patient';
$this->params['breadcrumbs'][] = ['label' => 'Old Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="old-patient-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
