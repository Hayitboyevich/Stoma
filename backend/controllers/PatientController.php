<?php

namespace backend\controllers;

use common\models\constants\UserRole;
use common\models\DeleteLog;
use common\models\DiscountRequest;
use common\models\Invoice;
use common\models\Media;
use common\models\Patient;
use common\models\PatientExamination;
use common\models\PriceList;
use common\models\PriceListItem;
use common\models\Reception;
use common\models\SmsGateway;
use common\models\Transaction;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PatientController implements the CRUD actions for Patient model.
 */
class PatientController extends Controller
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
                            'actions' => ['add-money-to-account'],
                            'allow' => true,
                            'roles' => ['patient_add_money_to_account']
                        ],
                        ['actions' => ['ajax-create'], 'allow' => true, 'roles' => ['patient_ajax_create']],
                        ['actions' => ['ajax-delete'], 'allow' => true, 'roles' => ['patient_ajax_delete']],
                        [
                            'actions' => ['ajax-update', 'change-photo', 'update', 'update-note'],
                            'allow' => true,
                            'roles' => ['patient_ajax_update']
                        ],
                        ['actions' => ['index', 'search'], 'allow' => true, 'roles' => ['patient_index']],
                        ['actions' => ['invoice-pay', 'finance'], 'allow' => true, 'roles' => ['invoice_pay']],
                        ['actions' => ['assign-discount'], 'allow' => true, 'roles' => ['assign_discount']],
                        ['actions' => ['request-discount'], 'allow' => true, 'roles' => ['request_discount']],
                        [
                            'actions' => ['new-examination-create'],
                            'allow' => true,
                            'roles' => ['new_examination_create']
                        ],
                        ['actions' => ['upload', 'view-file'], 'allow' => true, 'roles' => ['upload_patient_files']],
                        ['actions' => ['approve-discount-request'], 'allow' => true, 'roles' => ['approve_discount']],
                        ['actions' => ['get-balance'], 'allow' => true, 'roles' => ['view_patient_balance']],
                        ['actions' => ['delete-file'], 'allow' => true, 'roles' => ['delete_patient_file']],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Patient models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $get = Yii::$app->request->get();
        $data['discount_patients'] = array_key_exists('discount_patients', $get) ? $get['discount_patients'] : 0;
        $data['sort'] = array_key_exists('sort', $get) ? $get['sort'] : 'ASC';
        $patients = Patient::find()->where(['patient.deleted' => 0]);

        if ($data['discount_patients']) {
            $patients->andWhere(['IS NOT', 'discount', null]);
            $patients->andWhere(['!=', 'discount', 0]);
        }

        if ($data['sort']) {
            $patients->orderBy('discount ' . $data['sort'] . ', last_activity DESC');
        } else {
            $patients->orderBy('last_activity DESC');
        }

        $total_rows = $patients->count();
        $data['total_rows'] = $total_rows;
        $data['per_page'] = array_key_exists('per_page', $get) ? $get['per_page'] : 10;
        $data['page'] = array_key_exists('page', $get) ? $get['page'] : 1;

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

        $data['patients'] = $patients->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();
        $data['last_row_number'] = $data['offset'] + $data['per_page'] * $data['page'];

        if ($data['last_row_number'] > $data['total_rows']) {
            $data['last_row_number'] = $data['total_rows'];
        }

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || array_key_exists('per_page', $get);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    /**
     * Updates an existing Patient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id ID
     *
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        $invoice = array_key_exists('invoice_id', $this->request->get()) ? Invoice::findOne(
            $this->request->get('invoice_id')
        ) : null;

        $record = array_key_exists('record_id', $this->request->get()) ? Reception::findOne(
            $this->request->get('record_id')
        ) : null;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $priceLists = PriceList::find()->with([
            'items' => function ($query) {
                return $query->andWhere(
                    ['price_list_item.parent_id' => null, 'price_list_item.status' => PriceListItem::STATUS_ACTIVE]
                );
            }
        ])->all();

        $assistants = User::find()->where([
            'role' => UserRole::ROLE_ASSISTANT,
            'status' => User::STATUS_ACTIVE,
            'work_status' => User::WORK_STATUS_AVAILABLE
        ])->all();

        $doctors = User::find()->where([
            'role' => UserRole::ROLE_DOCTOR,
            'status' => User::STATUS_ACTIVE,
            'work_status' => User::WORK_STATUS_AVAILABLE
        ])->all();

        return $this->render('update', [
            'model' => $model,
            'invoice' => $invoice,
            'record' => $record,
            'priceLists' => $priceLists,
            'doctors' => $doctors,
            'assistants' => $assistants
        ]);
    }

    /**
     * Finds the Patient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     *
     * @return Patient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Patient
    {
        if (($model = Patient::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAjaxCreate(): array
    {
        $post = Yii::$app->request->post();
        $patient = new Patient();
        $patient->firstname = $post['first_name'];
        $patient->lastname = $post['last_name'];
        $patient->phone = $post['phone'];
        $patient->doctor_id = $post['doctor_id'];
        $patient->vip = $post['vip'] == 'vip';
        $patient->gender = $post['gender'];
        $patient->dob = $post['dob'];
        if ($patient->save()) {
            $output = ['status' => 'success', 'message' => $patient->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($patient->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionChangePhoto()
    {
        $user = Patient::findOne(Yii::$app->request->post('user_id'));
        $file = $_FILES['change-photo'];
        $media = new Media();
        $media->filename = $file['name'];
        $media->file_type = Media::getFileExtension($media->filename);
        if ($media->save()) {
            move_uploaded_file(
                $file['tmp_name'],
                Yii::getAlias('@app') . '/uploads/' . $media->id . '.' . $media->file_type
            );
        }
        $user->media_id = $media->id;
        $user->save();
        $this->redirect('/patient/update?id=' . $user->id);
    }

    public function actionAjaxUpdate()
    {
        $post = Yii::$app->request->post();
        $patient = Patient::findOne($post['patient_id']);
        $patient->firstname = $post['first_name'];
        $patient->lastname = $post['last_name'];
        $patient->phone = $post['phone'];
        $patient->doctor_id = $post['doctor_id'];
        $patient->vip = $post['vip'] == 'vip';
        $patient->gender = $post['gender'];
        $patient->dob = $post['dob'];
        if ($patient->save()) {
            $output = ['status' => 'success', 'message' => $patient->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($patient->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    /**
     * @return string[]
     */
    public function actionAjaxDelete(): array
    {
        $patients = Patient::find()->where(['IN', 'id', Yii::$app->request->post('ids')])->all();
        if (!empty($patients)) {
            foreach ($patients as $patient) {
                $deleteLog = new DeleteLog();
                $deleteLog->user_id = Yii::$app->user->identity->id;
                $deleteLog->object_type = 'patient';
                $deleteLog->object_id = $patient->id;
                $deleteLog->object_data = json_encode($patient->toArray());
                $deleteLog->name = Yii::$app->user->identity->firstname . ' ' . Yii::$app->user->identity->lastname;
                if ($deleteLog->save()) {
                    $patient->deleted = 1;
                    $patient->save();
                }
            }
        }

        $output = ['status' => 'success', 'message' => 'ok'];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionAddMoneyToAccount(): array
    {
        $post = $this->request->post();
        $patient = Patient::findOne($post['patient_id']);
        $model = new Transaction();
        $model->patient_id = $patient->id;
        $model->user_id = Yii::$app->user->identity->id;
        $model->amount = $post['amount'];
        $model->payment_method = $post['payment_method'];
        $model->type = Transaction::TYPE_ADD_MONEY;
        if ($post['is_foreign_currency'] == 'true') {
            $model->is_foreign_currency = Transaction::IS_FOREIGN_CURRENCY;
            $model->foreign_currency_rate = $post['foreign_currency_rate'];
            $model->foreign_currency_amount = $post['amount'];
            $model->amount = $post['amount'] * $post['foreign_currency_rate'];
        }

        if ($model->save()) {
            $output = ['status' => 'success', 'message' => $model->id];

//            $sms = new SmsGateway();
//            $sms->recipient = $patient->phone;
//            $sms->text = SmsGateway::formatSms([
//                'template' => 'money_credited',
//                'data' => [
//                    'fullname' => $patient->getFullName(),
//                    'amount' => $model->amount,
//                ]
//            ]);

//            $response = $sms->sendSms();
//            $log = SmsNotification::findOne($response['log_id']);
//            $log->object_id = $model->id;
//            $log->object_type = 'transaction';
//            $log->save();

        } else {
            $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionSearch(): array
    {
        $text = Yii::$app->request->get('text');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $parts = explode(' ', $text);
        $select[] = 'patient.id';
        $select[] = 'patient.firstname';
        $select[] = 'patient.lastname';
        $select[] = 'patient.phone';
        $select[] = 'patient.dob';
        $select[] = 'patient.vip';
        $select[] = 'patient.gender';
        if (Yii::$app->user->can('view_patient_discount')) {
            $select[] = 'discount';
        }
        $select[] = 'deleted';
        $patient = Patient::find()->select($select);

        if (Yii::$app->user->can('doctor')) {
            $patient->innerJoin('doctor_patient', 'doctor_patient.patient_id = patient.id');
            $patient->where(['doctor_patient.doctor_id' => Yii::$app->user->identity->id]);
        }

        foreach ($parts as $index => $part) {
            if ($index == 0) {
                $patient->where(['LIKE', 'concat(patient.firstname,patient.lastname,patient.phone)', $part]);
            } else {
                $patient->andWhere(['LIKE', 'concat(patient.firstname,patient.lastname,patient.phone)', $part]);
            }
        }

        $patient->andWhere(['patient.deleted' => Patient::NOT_DELETED])->joinWith('doctor');
        return $patient->asArray()->all();
    }

    /**
     * @return array|string[]
     */
    public function actionAssignDiscount(): array
    {
        $post = $this->request->post();
        $model = Patient::findOne($post['patient_id']);
        if (isset($model)) {
            $model->discount = $post['discount'];
            if ($model->save()) {
                $output = ['status' => 'success', 'message' => $model->id];
            } else {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'Patient not found'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionRequestDiscount(): array
    {
        $post = $this->request->post();
        $model = new DiscountRequest();
        $model->user_id = Yii::$app->user->identity->id;
        $model->patient_id = $post['patient_id'];
        $model->discount = $post['discount'];

        if ($model->save()) {
            $output = ['status' => 'success', 'message' => $model->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionFinance($id): string
    {
        $model = $this->findModel($id);

        $patients = Patient::find()
            ->select(['id', 'firstname', 'lastname', "concat(lastname, ' ', firstname) as name"])
            ->where(['deleted' => Patient::NOT_DELETED])
            ->asArray()
            ->all();

        return $this->render('_finance', [
            'model' => $model,
            'patients' => $patients
        ]);
    }

    public function actionUpload()
    {
        $post = Yii::$app->request->post();
        if (array_key_exists('file', $_FILES)) {
            $name = $_FILES['file']['name'];
            $pathInfo = pathinfo($name);
            $media = new Media();
            $media->filename = $name;
            $media->file_type = $pathInfo['extension'];
            $media->title = !empty($post['file_title']) ? $post['file_title'] : $pathInfo['filename'];
            $media->description = $post['file_description'];
            $media->object_type = 'patient';
            $media->object_id = $post['patient_id'];

            if ($media->save()) {
                $newName = $media->id . '.' . $pathInfo['extension'];
                move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    Yii::getAlias('@app') . '/uploads/' . $newName
                );
            }
        }
    }

    public function actionDeleteFile()
    {
        $post = Yii::$app->request->post();
        $file = Media::findOne($post['id']);
        if (isset($file)) {
            $filePath = Yii::getAlias('@app') . '/uploads/' . $file->filename;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file->delete();
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionNewExaminationCreate($patientId)
    {
        $patientExamination = new PatientExamination();
        $patient = Patient::findOne($patientId);

        if (!$patient) {
            throw new NotFoundHttpException();
        }

        if ($this->request->isPost) {
            $patientExamination->doctor_id = Yii::$app->user->identity->id;
            if ($patientExamination->load($this->request->post()) && $patientExamination->save()) {
                return $this->redirect(['patient/update', 'id' => $patientId]);
            }
        } else {
            $patientExamination->loadDefaultValues();
        }

        return $this->render('_new-examination-modal', [
            'patientExamination' => $patientExamination,
            'patientId' => $patientId
        ]);
    }

    public function actionApproveDiscountRequest(): array
    {
        $post = $this->request->post();
        $model = DiscountRequest::findOne($post['request_id']);

        $model->status = 'approved';
        $model->approved_user_id = Yii::$app->user->identity->id;
        $model->approved_time = date('Y-m-d H:i:s');
        $model->save(false);
        $model->patient->discount = $model->discount;
        if ($model->patient->save(false)) {
            $output = ['status' => 'success', 'message' => $model->patient->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($model->patient->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionUpdateNote($id): Response
    {
        $model = $this->findModel($id);
        $model->note = Yii::$app->request->post('note');
        $model->note_update_at = date('Y-m-d H:i:s');
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGetBalance($patientId): array
    {
        $model = Patient::findOne($patientId);
        if (isset($model)) {
            $output = [
                'status' => 'success',
                'balance' => number_format($model->getPrepayment(), 0, '', ' ')
            ];
        } else {
            $output = ['status' => 'fail', 'message' => 'Пациент не найден'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionViewFile($id)
    {
        $file = Media::findOne($id);
        if ($file !== null) {
            $filePath = Yii::getAlias('@app') . '/uploads/' . $file->id . '.' . $file->file_type;
            if (file_exists($filePath)) {
                // Определяем MIME-тип файла
                $mimeType = mime_content_type($filePath);

                // Устанавливаем заголовки для отдачи файла в браузере
                header("Content-Type: $mimeType");
                header("Content-Disposition: inline; filename=" . basename($filePath));

                // Отправляем содержимое файла в буфер вывода
                readfile($filePath);
            } else {
                // В случае, если файл не найден, выбрасываем исключение 404
                throw new NotFoundHttpException('The requested file does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
    }
}
