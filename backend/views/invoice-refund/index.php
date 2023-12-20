<?php

use common\models\InvoiceRefund;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\InvoiceRefundSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Invoice Refunds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-refund-index pl-4 pr-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Invoice Refund', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'invoice_id',
            'requested_user_id',
            'approved_or_declined_user_id',
            'approved_or_declined_at',
            'approved_or_declined_comment',
            'status',
            'comments',
            'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, InvoiceRefund $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
