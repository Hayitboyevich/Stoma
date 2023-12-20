<?php

namespace backend\controllers;

use common\models\PriceList;
use common\models\PriceListItem;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PriceListController implements the CRUD actions for PriceList model.
 */
class PriceListController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                'ajax-create',
                                'delete',
                                'index',
                                'view',
                                'ajax-get',
                                'ajax-update',
                                'excel'
                            ],
                            'allow' => true,
                            'roles' => ['price_list_manage']
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @param int|null $categoryId
     * @return string
     */
    public function actionIndex(int $categoryId = null): string
    {
        $model = PriceList::findOne($categoryId);
        $categories = PriceList::find()->where(['status' => PriceList::STATUS_ACTIVE])->all();

        $priceListItems = [];
        $priceListItemsList = [];
        if ($categoryId !== null) {
            $priceListItems = PriceListItem::find()
                ->select(['id', 'name', 'price_list_id', 'price', 'parent_id', 'is_group', 'status'])
                ->where(['price_list_id' => $categoryId, 'status' => PriceListItem::STATUS_ACTIVE, 'parent_id' => null])
                ->with([
                    'priceListItems' => function (Query $query) {
                        $query->select(['id', 'name', 'price_list_id', 'price', 'parent_id', 'status']);
                    }
                ])
                ->all();

            $priceListItemsList = array_filter($priceListItems, static function ($item) {
                return $item->parent_id === null && $item->is_group === PriceListItem::IS_NOT_GROUP;
            });
        }

        return $this->render('index', [
            'model' => $model,
            'categories' => $categories,
            'priceListItems' => $priceListItems,
            'priceListItemsList' => $priceListItemsList,
            'categoryId' => $categoryId
        ]);
    }

    public function actionAjaxGet($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = PriceList::findOne($id);

        if ($model === null) {
            return [
                'status' => 'fail',
                'message' => 'Category not found'
            ];
        }

        return [
            'status' => 'success',
            'model' => $model->toArray()
        ];
    }


    /**
     * @return array|string[]
     */
    public function actionAjaxCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PriceList();
        $model->load(Yii::$app->request->post(), '');

        if ($model->save()) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'fail',
            'message' => print_r($model->errors, true)
        ];
    }

    public function actionAjaxUpdate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'fail',
            'content' => $this->renderAjax('_edit-price-list-modal.php', [
                'model' => $model
            ])
        ];
    }

    public function actionExcel(): string
    {
        $this->layout = 'excel_blank';

        $priceLists = PriceList::find()->all();

        return $this->render('excel', [
            'priceLists' => $priceLists
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $model = PriceList::find()->where(['id' => $id, 'status' => PriceList::STATUS_ACTIVE])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $priceListItems = PriceListItem::find()
            ->select(['id', 'name', 'price_list_id', 'price', 'parent_id', 'is_group', 'status'])
            ->where(['price_list_id' => $id, 'status' => PriceListItem::STATUS_ACTIVE, 'parent_id' => null])
            ->with([
                'priceListItems' => function (Query $query) {
                    $query->select(['id', 'name', 'price_list_id', 'price', 'parent_id', 'status']);
                }
            ])
            ->all();

        $priceListItemsList = array_filter($priceListItems, static function ($item) {
            return $item->parent_id === null && $item->is_group === PriceListItem::IS_NOT_GROUP;
        });

        return $this->render('view', [
            'model' => $model,
            'priceListItems' => $priceListItems,
            'priceListItemsList' => $priceListItemsList
        ]);
    }

    /**
     * Deletes an existing PriceList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $model->status = PriceList::STATUS_INACTIVE;
        if ($model->save()) {
            PriceListItem::updateAll(['status' => PriceListItem::STATUS_INACTIVE], ['price_list_id' => $id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the PriceList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PriceList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): PriceList
    {
        if (($model = PriceList::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
