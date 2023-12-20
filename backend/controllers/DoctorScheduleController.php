<?php

namespace backend\controllers;

use common\models\DoctorSchedule;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DoctorScheduleController implements the CRUD actions for DoctorSchedule model.
 */
class DoctorScheduleController extends Controller
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
            ]
        );
    }


    /**
     * @param int|null $id
     * @return string
     */
    public function actionIndex(int $id = null): string
    {
        $query = DoctorSchedule::find()
            ->where(['doctor_id' => $id ?? Yii::$app->user->identity->id])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'doctorSchedules' => $query,
        ]);
    }

    /**
     * Displays a single DoctorSchedule model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DoctorSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new DoctorSchedule();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $doctorId = Yii::$app->request->get('doctor');
                if ($doctorId) {
                    $model->doctor_id = $doctorId;
                }
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DoctorSchedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $doctorId = Yii::$app->request->get('doctor');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($doctorId) {
                return $this->redirect(['index', 'id' => $doctorId]);
            } else {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DoctorSchedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DoctorSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DoctorSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): DoctorSchedule
    {
        if (($model = DoctorSchedule::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
