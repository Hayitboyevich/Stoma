<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\OldPatient $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Old Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="old-patient-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'card_number',
            'first_visit',
            'last_name',
            'first_name',
            'patronymic',
            'gender',
            'dob',
            'phone',
            'phone_home',
            'phone_work',
            'discount',
            'home_address',
            'doctor_id',
            'hygienist_id',
            'source',
            'recommended_patient',
            'recommended_user',
            'who_were_recommended',
            'patient_status',
            'debt',
            'credit',
            'recommendations_amount',
        ],
    ]) ?>

</div>
