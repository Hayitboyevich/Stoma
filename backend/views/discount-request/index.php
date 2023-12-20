<?php

use common\models\DiscountRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DiscountRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Discount Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-request-index pl-4 pr-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Discount Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            //'user_id',
            [
                'attribute' => 'user.fullName',
                'label' => 'Заявку создал'
            ],
            [
                'attribute' => 'patient.fullName',
                'label' => 'Пациент'
            ],
            [
                'attribute' => 'approvedUser.fullName',
                'label' => 'Заявку утвердил'
            ],
            //'patient_id',
            'discount',
            'status',
            //'approved_user_id',
            //'approved_user_id',
            'approved_time',
            //'created_at',
            [
                'attribute' => 'approve',
                'label' => 'Утвердить',
                'value' => static function ($model) {
                    return Html::button('утвердить', [
                        'class' => 'sj-btn sj-btn-success approve-discount-request',
                        'data-id' => $model->id
                    ]);
                },
                'format' => 'raw'
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => static function ($action, DiscountRequest $model) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]) ?>
</div>
