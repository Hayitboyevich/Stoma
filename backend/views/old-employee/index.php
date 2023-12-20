<?php

use common\models\OldEmployee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\OldEmployeeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Old Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="old-employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Old Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'last_name',
            'first_name',
            'patronymic',
            'dob',
            //'status',
            //'work_start_date',
            //'work_end_date',
            //'email:email',
            //'initials',
            //'phone',
            //'phone_home',
            //'phone_work',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, OldEmployee $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
