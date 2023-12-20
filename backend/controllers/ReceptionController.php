<?php

namespace backend\controllers;

use common\models\constants\UserRole;
use common\models\DoctorPatient;
use common\models\DoctorScheduleItem;
use common\models\Patient;
use common\models\PriceList;
use common\models\Reception;
use common\models\ReceptionSearch;
use common\models\User;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ReceptionController implements the CRUD actions for Reception model.
 */
class ReceptionController extends Controller
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
                        ['actions' => ['cancel-record'], 'allow' => true, 'roles' => ['reception_cancel_record']],
                        [
                            'actions' => ['index', 'week'],
                            'allow' => true,
                            'roles' => ['reception_index']
                        ],
                        [
                            'actions' => ['send-telegram-notification'],
                            'allow' => true,
                            'roles' => ['reception_send_telegram_notification']
                        ],
                        [
                            'actions' => ['send-sms-notification'],
                            'allow' => true,
                            'roles' => ['get-record', 'reception_send_sms_notification']
                        ],
                        ['actions' => ['manage'], 'allow' => true, 'roles' => ['admin']],
                        [
                            'actions' => ['new-record', 'ajax-search'],
                            'allow' => true,
                            'roles' => ['reception_new_record']
                        ],
                        ['actions' => ['remove-record'], 'allow' => true, 'roles' => ['reception_remove_record']],
                        [
                            'actions' => ['get-record', 'the-patient-came'],
                            'allow' => true,
                            'roles' => ['reception_view_record_details']
                        ],
                        ['actions' => ['start-admission', 'finish-admission'], 'allow' => true, 'roles' => ['doctor']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Reception models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $doctors = User::find()
            ->where(
                [
                    'role' => UserRole::ROLE_DOCTOR,
                    'status' => User::STATUS_ACTIVE
                ]
            )
            ->all();
        $timeFrom = DoctorScheduleItem::find()->min('time_from');
        $timeTo = DoctorScheduleItem::find()->max('time_to');

        $priceLists = PriceList::find()->where(['status' => PriceList::STATUS_ACTIVE])->all();
        $maxDob = date('Y-m-d', strtotime('-1 day'));

        return $this->render('index', [
            'doctors' => $doctors,
            'priceLists' => $priceLists,
            'timeFrom' => $timeFrom,
            'timeTo' => $timeTo,
            'maxDob' => $maxDob,
        ]);
    }

    /**
     * @param string|null $start_date
     * @param string|null $end_date
     * @param int|null $doctor_id
     * @return string
     */
    public function actionWeek(string $start_date = null, string $end_date = null, int $doctor_id = null): string
    {
        $doctors = User::find()
            ->where(
                [
                    'role' => UserRole::ROLE_DOCTOR,
                    'status' => User::STATUS_ACTIVE
                ]
            )
            ->all();

        $priceLists = PriceList::find()->where(['status' => PriceList::STATUS_ACTIVE])->all();

        return $this->render('week', [
            'recordsByDoctor' => (new Reception())->getWeekRecords($start_date, $end_date, $doctor_id),
            'doctors' => $doctors,
            'priceLists' => $priceLists
        ]);
    }

    /**
     * Finds the Reception model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Reception the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Reception
    {
        if (($model = Reception::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionNewRecord(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $checkAvailability = self::checkDoctorAvailability($post);

        if ($checkAvailability === 'schedule_not_found') {
            return ['status' => 'fail', 'message' => 'Расписание врача не заполнено'];
        }
        if ($checkAvailability === 'not_available') {
            return ['status' => 'fail', 'message' => 'Врач не принимает в это время'];
        }

        if (empty($post['patient_id']) || $post['patient_id'] == 0) {
            $patient = new Patient();
            $patient->lastname = $post['last_name'];
            $patient->firstname = $post['first_name'];
            $patient->phone = $post['phone'];
            $patient->gender = $post['gender'];
            $patient->dob = $post['birthday'];
            if (!$patient->save()) {
                return ['status' => 'fail', 'message' => print_r($patient->errors, true)];
            }
        } else {
            $patient = Patient::findOne($post['patient_id']);
        }

        if (!empty($post['record_id'])) {
            $record = Reception::findOne($post['record_id']);
        } else {
            $record = new Reception();
        }

        $record->patient_id = $patient->id;
        $record->doctor_id = $post['doctor_id'];
        $record->category_id = $post['category_id'];
        $record->comment = $post['comments'];
        $record->record_date = $post['date'];
        $record->record_time_from = $post['time_from'];
        $record->record_time_to = $post['time_to'];
        $record->duration = $post['duration'];

        if ($post['sms'] == 'true') {
            $record->sms = $post['sms_time'];
        }

        if ($record->save()) {
            $doctorPatient = DoctorPatient::findOne([
                'doctor_id' => $record->doctor_id,
                'patient_id' => $record->patient_id
            ]);
            if (!$doctorPatient) {
                $doctorPatient = new DoctorPatient();
                $doctorPatient->doctor_id = $record->doctor_id;
                $doctorPatient->patient_id = $record->patient_id;
                $doctorPatient->save();
            }
            $output = ['status' => 'success'];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($record->errors, true)];
        }

        return $output;
    }

    /**
     * @return string
     */
    public function actionManage(): string
    {
        $this->layout = 'native-main';
        $searchModel = new ReceptionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetRecord($id)
    {
        $model = Reception::find()
            ->joinWith('doctor')
            ->joinWith('patient')
            ->joinWith('category')
            ->where(['reception.id' => $id])
            ->asArray()
            ->one();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionRemoveRecord($id): bool
    {
        $model = Reception::findOne($id);
        if (isset($model) && $model->saveDeleteLog()) {
            $model->delete();
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionSendSmsNotification($id): array
    {
        $model = Reception::findOne($id);
        $response = $model->sendSmsNotification();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionSendTelegramNotification($id): array
    {
        $model = Reception::findOne($id);
        $response = $model->sendTelegramNotification();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    /**
     * @return array|string[]
     */
    public function actionCancelRecord(): array
    {
        $post = Yii::$app->request->post();
        $model = Reception::findOne($post['id']);

        if (isset($model)) {
            $model->canceled = Reception::CANCELED;
            $model->cancel_reason = $post['reason'];
            if ($model->save()) {
                $output = ['status' => 'success'];
            } else {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'Запись не найдена'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    /**
     * @param $id
     * @return array|string[]
     */
    public function actionThePatientCame($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Reception::findOne($id);
        if (isset($model)) {
            $model->state = 'patient_came';
            if ($model->save()) {
                $output = ['status' => 'success'];
                $model->patient->last_visited = date('Y-m-d H:i:s');
                $model->patient->save(false);
            } else {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'Запись не найдена'];
        }

        return $output;
    }

    /**
     * @param $id
     * @return array|string[]
     */
    public function actionStartAdmission($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Reception::findOne($id);
        if (isset($model)) {
            $model->state = 'admission_started';
            if ($model->save()) {
                $output = ['status' => 'success'];
            } else {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'Запись не найдена'];
        }
        return $output;
    }

    /**
     * @param $id
     * @return array|string[]
     */
    public function actionFinishAdmission($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Reception::findOne($id);
        if (isset($model)) {
            $model->state = 'admission_finished';
            if ($model->save()) {
                $output = ['status' => 'success'];
            } else {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'Запись не найдена'];
        }
        return $output;
    }

    /**
     * @param $data
     * @return string
     * @throws Exception
     */
    public static function checkDoctorAvailability($data): string
    {
        $weekday = strtolower(date('D', strtotime($data['date'])));
        $user = User::findOne($data['doctor_id']);
        $schedule_id = $user->getActualScheduleId();
        if (!$schedule_id) {
            return 'schedule_not_found';
        }
        $sql = "SELECT * 
FROM `doctor_schedule_item` 
WHERE `doctor_schedule_id` = {$schedule_id}
AND weekday = '{$weekday}'
AND TIME_FORMAT('{$data['time_from']}','%H:%i') >= TIME_FORMAT(time_from,'%H:%i')
AND TIME_FORMAT('{$data['time_to']}','%H:%i') <= TIME_FORMAT(time_to,'%H:%i')";

        $result = Yii::$app->db->createCommand($sql)->execute();
        if ($result > 0) {
            return 'available';
        }

        return 'not_available';
    }

    public function actionAjaxSearch(): array
    {
        $lastname = Yii::$app->request->get('lastname', '');
        $firstname = Yii::$app->request->get('firstname', '');

        $query = Patient::find()->where(['like', 'lastname', $lastname]);

        if (!empty($firstname)) {
            $query->andWhere(['like', 'firstname', $firstname]);
        }

        $patients = $query->all();

        $response = [];
        foreach ($patients as $patient) {
            $response[] = [
                'label' => "{$patient->lastname} {$patient->firstname} {$patient->phone}",
                'firstname' => $patient->firstname,
                'lastname' => $patient->lastname,
                'value' => $patient->id,
                'phone' => $patient->phone,
                'id' => $patient->id,
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
}
