<?php

namespace backend\controllers;

use common\models\Config;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends Controller
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
                    'class' => VerbFilter::class
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        ['actions' => ['create'], 'allow' => true, 'roles' => ['config_create']],
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['config_index']],
                        ['actions' => ['update'], 'allow' => true, 'roles' => ['config_update']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Config models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $configs = Config::find()->all();

        return $this->render('index', [
            'configs' => $configs
        ]);
    }

    /**
     * Creates a new Config model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
//    public function actionCreate()
//    {
//        $model = new Config();
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'key' => $model->key]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Updates an existing Config model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $key Key
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $key)
    {
        $model = $this->findModel($key);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены!');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $key Key
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $key): Config
    {
        if (($model = Config::findOne(['key' => $key])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
