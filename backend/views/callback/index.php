<?php

use common\models\Callback;
use common\widgets\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CallbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Callbacks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="patients">

    <div class="patients__search">
        <div class="patients__left">
            <div class="patients__filter">
                <img src="/img/filter.svg" alt="">
            </div>
            <div class="patients__input">
                <img src="/img/search_icon.svg" alt="">
                <input type="text" placeholder="Поиск">
            </div>
        </div>
        <div class="patients__right">
            <?= Html::a('Create Callback<span><img src="/img/icon_plus.svg" alt=""></span>', ['create'], ['class' => 'btn_top btn_blue']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'patient_id',
                'value' => function($model){
                    return "{$model->patient->phone} {$model->patient->lastname} {$model->patient->firstname}";
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->status ? "<span style='background: green;padding: 3px 5px;color: #fff;'>Обработан</span>" : "Не обработан";
                },
                'format' => 'HTML'
            ],
            'comments:ntext',
            'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Callback $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
