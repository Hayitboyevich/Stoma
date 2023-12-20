<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Callback */

$this->title = 'Create Callback';
$this->params['breadcrumbs'][] = ['label' => 'Callbacks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callback-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
