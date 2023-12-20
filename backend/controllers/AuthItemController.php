<?php

namespace backend\controllers;

use Yii;
use common\models\AuthItem;
use common\models\AuthItemSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        $this->layout = 'native-main';
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
                        ['actions' => ['role'], 'allow' => true, 'roles' => ['auth_item_role']],
                        ['actions' => ['update-role'], 'allow' => true, 'roles' => ['auth_item_update_role']],
                    ],
                ],
            ]
        );
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name Name
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $name): AuthItem
    {
        if (($model = AuthItem::findOne(['name' => $name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRole(): string
    {
        $searchModel = new AuthItemSearch(['type' => 1]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('role', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateRole(string $id)
    {
        $post = Yii::$app->request->post();

        $model = $this->findModel($id);

        if ($model->load($post) && $model->save()) {
            $model->updatePermissions($post);
            return $this->redirect(['update-role', 'id' => $model->name]);
        }

        return $this->render('update-role', [
            'model' => $model,
        ]);
    }
}
