<?php

use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="patients">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'      => [
            'style' => 'height: 800px; overflow-y: auto',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return '/auth-item/update-role?id='.$model->name;
                },
                'template' => '{update}'
            ],
            //['class' => 'yii\grid\SerialColumn'],

            'name',
            //'type',
            'description:ntext',
            //'rule_name',
            //'data',
            //'created_at',
            //'updated_at',


        ],
    ]); ?>
</div>
