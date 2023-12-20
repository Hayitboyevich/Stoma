<?php

namespace backend\controllers;

use common\models\PriceList;
use common\models\PriceListItem;
use common\models\TechnicianPriceList;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PriceListItemController implements the CRUD actions for PriceListItem model.
 */
class PriceListItemController extends Controller
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
                                'ajax-update',
                                'delete',
                                'index',
                                'update',
                                'ajax-get',
                                'ajax-create-group',
                                'ajax-update-group',
                                'ajax-get-group',
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
     * Lists all PriceListItem models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();

        $priceLists = PriceList::find()->where(['status' => PriceList::STATUS_ACTIVE])->all();
        $priceListItems = PriceListItem::find()->where(['status' => PriceListItem::STATUS_ACTIVE]);
        $technicianPriceLists = TechnicianPriceList::find()->where(['status' => TechnicianPriceList::STATUS_ACTIVE])
            ->all();

        $data['price_list_id'] = array_key_exists('price_list_id', $get) ? $get['price_list_id'] : null;
        if (!empty($data['price_list_id'])) {
            $priceListItems->andWhere(['price_list_id' => $data['price_list_id']]);
        }

        $total_rows = $priceListItems->count();
        $data['total_rows'] = $total_rows;
        $data['per_page'] = array_key_exists('per_page', $get) ? $get['per_page'] : 10;
        $data['page'] = array_key_exists('page', $get) ? $get['page'] : 1;
        $data['sort'] = array_key_exists('sort', $get) ? $get['sort'] : 'asc';

        $data['offset'] = $data['per_page'] * ($data['page'] - 1);

        if ($data['per_page'] >= $data['total_rows']) {
            $data['total_pages'] = 1;
        } else {
            $add = 0;
            if ($data['total_rows'] % $data['per_page'] > 0) {
                $add = 1;
            }
            $data['total_pages'] = round($data['total_rows'] / $data['per_page']) + $add;
        }

        $data['price_list_items'] = $priceListItems->orderBy(['name' => $data['sort'] == 'asc' ? SORT_ASC : SORT_DESC])
            ->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();

        $data['last_row_number'] = $data['offset'] + $data['per_page'] * $data['page'];

        if ($data['last_row_number'] > $data['total_rows']) {
            $data['last_row_number'] = $data['total_rows'];
        }

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || array_key_exists('per_page', $get);

        return $this->render('index', [
            'data' => $data,
            'priceLists' => $priceLists,
            'technicianPriceLists' => $technicianPriceLists
        ]);
    }

    public function actionAjaxGet($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = PriceListItem::findOne($id);

        if ($model === null) {
            return [
                'status' => 'fail',
                'message' => 'Category not found'
            ];
        }

        $priceLists = PriceList::find()->where(['status' => PriceList::STATUS_ACTIVE])->all();
        $technicianPriceLists = TechnicianPriceList::find()
            ->where(['status' => TechnicianPriceList::STATUS_ACTIVE])
            ->all();

        return [
            'status' => 'success',
            'content' => $this->renderAjax('_content-price-list-modal', [
                'model' => $model,
                'priceLists' => $priceLists,
                'technicianPriceLists' => $technicianPriceLists
            ])
        ];
    }

    public function actionAjaxCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PriceListItem();
        $model->scenario = PriceListItem::SCENARIO_IS_NOT_GROUP;
        $model->load(Yii::$app->request->post(), '');

        if ($model->save()) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'fail',
            'message' => implode("<br/>", ArrayHelper::getColumn($model->errors, 0, false))
        ];
    }

    public function actionAjaxUpdate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->scenario = PriceListItem::SCENARIO_IS_NOT_GROUP;
        $model->load(Yii::$app->request->post(), '');

        if ($model->save()) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'fail',
            'message' => implode("<br/>", ArrayHelper::getColumn($model->errors, 0, false))
        ];
    }

    public function actionAjaxGetGroup($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = PriceListItem::findOne($id);

        if ($model === null) {
            return [
                'status' => 'fail',
                'message' => 'Category not found'
            ];
        }

        $priceListItemsList = PriceListItem::find()
            ->select(['id', 'name', 'parent_id'])
            ->where(['OR', ['parent_id' => $model->id], ['parent_id' => null]])
            ->andWhere(
                [
                    'status' => PriceListItem::STATUS_ACTIVE,
                    'is_group' => PriceListItem::IS_NOT_GROUP,
                    'price_list_id' => $model->price_list_id
                ]
            )
            ->all();

        return [
            'status' => 'success',
            'content' => $this->renderAjax('_edit-price-list-group-modal', [
                'priceListItemsList' => $priceListItemsList,
                'model' => $model
            ])
        ];
    }

    public function actionAjaxCreateGroup(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PriceListItem();
        $model->is_group = PriceListItem::IS_GROUP;
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            PriceListItem::updateAll(['parent_id' => $model->id], ['id' => Yii::$app->request->post('price_lists')]);

            return [
                'status' => 'success',
                'message' => 'Price list item created successfully',
            ];
        }

        return [
            'status' => 'fail',
            'message' => print_r($model->errors, true)
        ];
    }

    public function actionAjaxUpdateGroup(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            PriceListItem::updateAll(['parent_id' => null], ['parent_id' => $model->id]);
            PriceListItem::updateAll(['parent_id' => $model->id], ['id' => Yii::$app->request->post('price_lists')]);

            return ['status' => 'success'];
        }

        $priceListItemsList = PriceListItem::find()
            ->select(['id', 'name', 'parent_id'])
            ->where(['OR', ['parent_id' => $model->id], ['parent_id' => null]])
            ->andWhere(['status' => PriceListItem::STATUS_ACTIVE, 'is_group' => PriceListItem::IS_NOT_GROUP])
            ->all();

        return [
            'status' => 'fail',
            'content' => $this->renderAjax('_edit-price-list-group-modal', [
                'model' => $model,
                'priceListItemsList' => $priceListItemsList
            ])
        ];
    }

    public function actionExcel(int $categoryId): string
    {
        $this->layout = 'excel_blank';

        $priceListItems = PriceListItem::find()
            ->where(['status' => PriceListItem::STATUS_ACTIVE, 'parent_id' => null, 'price_list_id' => $categoryId])
            ->with(['priceList', 'priceListItems'])
            ->all();

        return $this->render('excel', [
            'priceListItems' => $priceListItems
        ]);
    }

    /**
     * Updates an existing PriceListItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PriceListItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $model->status = PriceListItem::STATUS_INACTIVE;

        if ($model->is_group) {
            PriceListItem::updateAll(['parent_id' => null], ['parent_id' => $model->id]);
        }

        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the PriceListItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PriceListItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): PriceListItem
    {
        if (($model = PriceListItem::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
