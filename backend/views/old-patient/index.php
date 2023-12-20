<?php

use common\models\OldPatient;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\OldPatientSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Old Patients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="old-patient-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Old Patient', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'card_number',
            'first_visit',
            'last_name',
            'first_name',
            //'patronymic',
            //'gender',
            //'dob',
            //'phone',
            //'phone_home',
            //'phone_work',
            //'discount',
            //'home_address',
            //'doctor_id',
            //'hygienist_id',
            //'source',
            //'recommended_patient',
            //'recommended_user',
            //'who_were_recommended',
            //'patient_status',
            //'debt',
            //'credit',
            //'recommendations_amount',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, OldPatient $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
