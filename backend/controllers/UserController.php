<?php

namespace backend\controllers;

use common\models\constants\UserRole;
use common\models\DoctorCategory;
use common\models\DoctorPercent;
use common\models\DoctorSchedule;
use common\models\DoctorScheduleItem;
use common\models\EmployeeSalary;
use common\models\Media;
use common\models\PriceList;
use common\models\Reception;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        ['actions' => ['ajax-create'], 'allow' => true, 'roles' => ['user_ajax_create']],
                        ['actions' => ['ajax-delete'], 'allow' => true, 'roles' => ['user_ajax_delete']],
                        [
                            'actions' => ['ajax-update', 'change-photo', 'update'],
                            'allow' => true,
                            'roles' => ['user_ajax_update']
                        ],
                        [
                            'actions' => ['doctor-schedule-add', 'doctor-schedule-item-add', 'get-schedule-item'],
                            'allow' => true,
                            'roles' => ['user_doctor_schedule_add']
                        ],
                        ['actions' => ['index', 'index2', 'index3', 'search'], 'allow' => true, 'roles' => ['user_index']],
                        [
                            'actions' => ['records', 'timetable', 'get-records', 'current-date-record'],
                            'allow' => true,
                            'roles' => ['doctor', 'admin', 'director']
                        ],
                        [
                            'actions' => ['update-salary-percents'],
                            'allow' => true,
                            'roles' => ['update_salary_percents']
                        ],
                        ['actions' => ['change-password'], 'allow' => true, 'roles' => ['?', '@']],
                        ['actions' => ['change-permission'], 'allow' => true, 'roles' => ['manage_user_permissions']],
                        ['actions' => ['make-schedule-actual', 'navbar-setting'], 'allow' => true, 'roles' => ['@']],
                        ['actions' => ['salary'], 'allow' => true, 'roles' => ['view_salary']],
                    ],
                ],
            ]
        );
    }

    public function actionIndex(): string
    {
        $text = Yii::$app->request->get('text');
        $sort = Yii::$app->request->get('sort');
        if (!Yii::$app->user->can('Director')) {
            $users = User::find()
                ->select(['id', 'firstname', 'lastname', 'role', 'work_status'])
                ->where(['status' => User::STATUS_ACTIVE])
                ->andWhere(['role' => [UserRole::ROLE_DOCTOR, UserRole::ROLE_ASSISTANT]])
                ->with('percents');
            if ($text) {
                $users->andWhere(['like', 'firstname', $text]);
                $users->orWhere(['like', 'lastname', $text]);
                $users->orWhere(['like', 'phone', $text]);
            }
            if ($sort) {
                $users->orderBy(['lastname' => (int)$sort]);
            }
            $users = $users->all();

            $priceLists = PriceList::find()->all();

            $array = [];
            foreach ($users as $user) {
                $array[$user->id] = [
                    'id' => $user->id,
                    'fullName' => $user->getFullName(),
                    'role' => $user->role,
                    'work_status' => $user->work_status,
                    'doctor_percent' => array_fill_keys(array_map(fn($priceList) => $priceList->id, $priceLists), 0),
                ];

                foreach ($user->percents as $percent) {
                    $array[$user->id]['doctor_percent'][$percent->price_list_id] = $percent->percent;
                }
            }

            return $this->render('index-director', [
                'users' => $array,
                'priceLists' => $priceLists,
            ]);
        } else {
            $users = User::find()
                ->where(['status' => User::STATUS_ACTIVE])
                ->andWhere(['<>', 'role', UserRole::ROLE_API]);
            if ($text) {
                $users->andWhere(['like', 'firstname', $text]);
                $users->orWhere(['like', 'lastname', $text]);
                $users->orWhere(['like', 'phone', $text]);
            }
            if ($sort) {
                $users->orderBy(['lastname' => (int)$sort]);
            }
            $users = $users->all();

            return $this->render('index', [
                'users' => $users,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $assistants = [];
        if ($model->role === UserRole::ROLE_DOCTOR) {
            $assistants = User::find()
                ->where(
                    [
                        'role' => UserRole::ROLE_ASSISTANT,
                        'status' => User::STATUS_ACTIVE,
                        'work_status' => User::WORK_STATUS_AVAILABLE
                    ]
                )
                ->all();
        }

        return $this->render('update', [
            'model' => $model,
            'assistants' => $assistants
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionNavbarSetting(): void
    {
        $session = Yii::$app->session;
        $navbarStatus = $session->get('navbar');
        $newStatus = $navbarStatus === 'active' ? 'inactive' : 'active';
        $session->set('navbar', $newStatus);
    }

    public function actionAjaxCreate(): array
    {
        $post = Yii::$app->request->post();
        $user = new User();
        $user->lastname = $post['last_name'];
        $user->firstname = $post['first_name'];
        $user->phone = User::getOnlyNumbers($post['phone']);
        $user->username = User::getOnlyNumbers($post['phone']);
        $user->auth_key = \Yii::$app->security->generateRandomString();
        $user->password_hash = \Yii::$app->security->generatePasswordHash($post['password']);
        $user->email = $post['email'];
        $user->status = 10;
        $user->role = $post['role'];
        $user->work_status = $post['work_status'];
        $user->work_start_date = $post['work_start_date'];
        $user->dob = $post['dob'];

        if ($user->save()) {
            $output = ['status' => 'success', 'message' => $user->id];
            if (!empty($post['categories'])) {
                foreach ($post['categories'] as $category_id) {
                    if (!DoctorCategory::findOne(['doctor_id' => $user->id, 'category_id' => $category_id])) {
                        $dc = new DoctorCategory();
                        $dc->doctor_id = $user->id;
                        $dc->category_id = $category_id;
                        $dc->save();
                    }
                }
            }
        } else {
            $output = ['status' => 'fail', 'message' => print_r($user->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionAjaxDelete(): int
    {
        return User::updateAll(['status' => User::STATUS_DELETED], ['IN', 'id', Yii::$app->request->post('ids')]);
    }

    public function actionAjaxUpdate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $user = User::findOne($post['user_id']);

        if (!isset($user)) {
            return ['status' => 'fail', 'message' => 'Пользователь не найден'];
        }

        $user->lastname = $post['last_name'];
        $user->firstname = $post['first_name'];
        $user->username = User::getOnlyNumbers($post['phone']);
        $user->phone = User::getOnlyNumbers($post['phone']);
        $user->email = $post['email'];
        $user->role = $post['role'];
        $user->work_status = $post['work_status'];
        $user->work_start_date = $post['work_start_date'];
        $user->dob = $post['dob'];
        $user->status = User::STATUS_ACTIVE;

        if (!empty($post['password'])) {
            $user->password_hash = \Yii::$app->security->generatePasswordHash($post['password']);
        }
        if ($user->save()) {
            $output = ['status' => 'success', 'message' => $user->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($user->errors, true)];
        }

        DoctorCategory::deleteAll(['doctor_id' => $user->id]);
        if (!empty($post['categories'])) {
            foreach ($post['categories'] as $category_id) {
                if (!DoctorCategory::findOne(['doctor_id' => $user->id, 'category_id' => $category_id])) {
                    $dc = new DoctorCategory();
                    $dc->doctor_id = $user->id;
                    $dc->category_id = $category_id;
                    $dc->save();
                }
            }
        }

        return $output;
    }

    public function actionChangePhoto(): void
    {
        $user = User::findOne(Yii::$app->request->post('user_id'));
        if ($user === null) {
            $this->redirect('/user/index');
        }

        $file = $_FILES['change-photo'];
        $media = new Media();
        $media->filename = $file['name'];
        $media->file_type = Media::getFileExtension($media->filename);
        if ($media->save()) {
            move_uploaded_file(
                $file['tmp_name'],
                Yii::getAlias('@app') . '/uploads/' . $media->id . '.' . $media->file_type
            );

            $source = Yii::getAlias('@app') . '/uploads/' . $media->id . '.' . $media->file_type;
            $image = Image::getImagine()->open($source);
            $image->save($source, ['quality' => 20]);
        }
        $user->media_id = $media->id;
        $user->save();
        $this->redirect('/user/update?id=' . $user->id);
    }

    /**
     * @return array|string[]
     */
    public function actionDoctorScheduleAdd(): array
    {
        $post = Yii::$app->request->post();
        $doctor = User::findOne($post['doctor_id']);

        if ($doctor === null) {
            return ['status' => 'fail', 'message' => 'Доктор не найден'];
        }

        $model = new DoctorSchedule();
        $model->date_from = $post['date_from'];
        $model->date_to = $post['date_to'];
        $model->doctor_id = $post['doctor_id'];
        $model->current = empty($doctor->schedules) ? DoctorSchedule::ACTIVE_SCHEDULE : DoctorSchedule::INACTIVE_SCHEDULE;
        if ($model->save()) {
            $output = ['status' => 'success', 'message' => $model->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    /**
     * @return array
     */
    public function actionDoctorScheduleItemAdd(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $model = DoctorScheduleItem::find()->where(
            ['doctor_schedule_id' => $post['doctor_schedule_id'], 'weekday' => $post['weekday']]
        )->one();

        if (!isset($model)) {
            $model = new DoctorScheduleItem();
        }

        $model->time_from = $post['time_from'];
        $model->time_to = $post['time_to'];
        $model->weekday = $post['weekday'];
        $model->doctor_schedule_id = $post['doctor_schedule_id'];
        if ($model->save()) {
            $output = ['status' => 'success', 'message' => $model->id];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
        }

        return $output;
    }

    /**
     * @return array|string[]
     * @throws Exception
     */
    public function actionChangePassword(): array
    {
        $post = Yii::$app->request->post();
        $user = User::findOne(['username' => User::getOnlyNumbers($post['login'])]);
        if ($user) {
            if (!$user->resetCodeNotExpired()) {
                $output = ['status' => 'fail', 'message' => 'reset_code_expired'];
            } else {
                if ($post['password1'] != $post['password2']) {
                    $output = ['status' => 'fail', 'message' => 'passwords_different'];
                } else {
                    if ($user->reset_password_code === $post['code']) {
                        $user->password_hash = \Yii::$app->security->generatePasswordHash($post['password1']);
                        if ($user->save()) {
                            $output = ['status' => 'success', 'message' => 'password_change_success'];
                        } else {
                            $output = ['status' => 'fail', 'message' => print_r($user->errors, true)];
                        }
                    } else {
                        $output = ['status' => 'fail', 'message' => 'invalid_code'];
                    }
                }
            }
        } else {
            $output = ['status' => 'fail', 'message' => 'user_not_found'];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    public function actionRecords(): string
    {
        $url_date = Yii::$app->request->get('date');
        $data['date'] = !empty($url_date) ? $url_date : date('Y-m-d');
        $data['doctor_id'] = Yii::$app->user->identity->id;
        $data['weekday'] = strtolower(date('D', strtotime($data['date'])));
        $data['weekdays'] = [
            'mon' => ['day_name' => 'Понедельник', 'day_num' => 1],
            'tue' => ['day_name' => 'Вторник', 'day_num' => 2],
            'wed' => ['day_name' => 'Среда', 'day_num' => 3],
            'thu' => ['day_name' => 'Четверг', 'day_num' => 4],
            'fri' => ['day_name' => 'Пятница', 'day_num' => 5],
            'sat' => ['day_name' => 'Суббота', 'day_num' => 6],
            'sun' => ['day_name' => 'Воскресенье', 'day_num' => 7],
        ];

        $current_day_num = $data['weekdays'][$data['weekday']]['day_num'];
        for ($day = 1; $day < $current_day_num; $day++) {
            $days_ago = $day - $current_day_num;
            $data['all_days'][] = date('Y-m-d', strtotime("{$days_ago} days", strtotime($data['date'])));
        }

        for ($day = $current_day_num; $day >= $current_day_num && $day < 8; $day++) {
            $days_ago = $day - $data['weekdays'][$data['weekday']]['day_num'];
            $data['all_days'][] = date('Y-m-d', strtotime("{$days_ago} days", strtotime($data['date'])));
        }

        $data['prev_week'] = date('Y-m-d', strtotime('-1 days', strtotime($data['all_days'][0])));
        $data['next_week'] = date('Y-m-d', strtotime('+1 days', strtotime($data['all_days'][6])));
        $data['current_date'] = date('Y-m-d');

        return $this->render('_records', ['data' => $data]);
    }

    public function actionCurrentDateRecord(): string
    {
        $data['date'] = date('Y-m-d');
        $data['doctor_id'] = Yii::$app->user->identity->id;

        $patients = Reception::getCurrentDateRecords(['doctor_id' => $data['doctor_id'], 'date' => $data['date']]);

        $records = [];
        if (is_array($patients)) {
            $recordIds = explode(',', $patients['record_ids']);
            $records = Reception::find()
                ->where(['IN', 'id', $recordIds])
                ->orderBy(['record_time_from' => SORT_ASC])
                ->all();
        }

        return $this->render('_current_date_records', compact('data', 'records'));
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionTimetable(): string
    {
        $model = User::findOne(Yii::$app->request->get('id') ?? Yii::$app->user->identity->id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $doctorScheduleId = null;
        if (!empty($this->request->get('doctor_schedule_id'))) {
            $doctorScheduleId = $this->request->get('doctor_schedule_id');
        } elseif ($currentSchedule = DoctorSchedule::findOne(['doctor_id' => $model->id, 'current' => DoctorSchedule::ACTIVE_SCHEDULE])) {
            $doctorScheduleId = $currentSchedule->id;
        }
        return $this->render('timetable', [
            'model' => $model,
            'doctor_schedule_id' => $doctorScheduleId
        ]);
    }

    public function actionGetRecords(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $post['doctor_id'] = Yii::$app->user->identity->id;
        $doctor = Reception::getRecordsByDoctorWeek($post);
        if (!$doctor) {
            return [];
        }
        $recordIds = explode(',', $doctor['record_ids']);
        return Reception::find()->where(['IN', 'id', $recordIds])->all();
    }

    /**
     * @return array|string[]
     */
    public function actionUpdateSalaryPercents(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
        $output = ['status' => 'success', 'message' => 'success'];
        foreach ($post['items'] as $item) {
            if (empty($item['percent']) && ($item['percent'] < 0 || $item['percent'] == '')) {
                continue;
            }

            $model = DoctorPercent::findOne(['user_id' => $post['user_id'], 'price_list_id' => $item['cat_id']]);
            if (!$model) {
                $model = new DoctorPercent();
            }

            $model->user_id = $post['user_id'];
            $model->price_list_id = $item['cat_id'];
            $model->percent = $item['percent'];
            if (!$model->save()) {
                $output = ['status' => 'fail', 'message' => print_r($model->errors, true)];
                break;
            }
        }

        return $output;
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    public function actionChangePermission(): array
    {
        $post = Yii::$app->request->post();
        $permission = Yii::$app->authManager->getPermission($post['permission']);
        if ($post['status'] == 'on') {
            Yii::$app->authManager->assign($permission, $post['user_id']);
        } else {
            Yii::$app->authManager->revoke($permission, $post['user_id']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['status' => 'success', 'message' => 'success'];
    }

    /**
     * @return array|string[]
     */
    public function actionMakeScheduleActual(): array
    {
        /**@var $item DoctorSchedule */
        $post = Yii::$app->request->post();

        $current = DoctorSchedule::find()
            ->where(['doctor_id' => Yii::$app->user->identity->id, 'current' => DoctorSchedule::ACTIVE_SCHEDULE])
            ->all();

        if (!empty($current)) {
            foreach ($current as $item) {
                $item->current = DoctorSchedule::INACTIVE_SCHEDULE;
                $item->save(false);
            }
        }

        $schedule = DoctorSchedule::findOne($post['schedule_id']);
        if (!$schedule) {
            return ['status' => 'fail', 'message' => 'Расписание не найдено'];
        }
        $schedule->current = 1;
        if ($schedule->save(false)) {
            $output = ['status' => 'success', 'message' => 'actual_schedule_set'];
        } else {
            $output = ['status' => 'fail', 'message' => print_r($schedule->errors, true)];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $output;
    }

    /**
     * @return array
     */
    public function actionGetScheduleItem(): array
    {
        $get = Yii::$app->request->get();
        $weekDay = $get['week_day'];
        $doctorScheduleId = $get['doctor_schedule_id'];
        $doctorScheduleItem = DoctorScheduleItem::find()
            ->where(['weekday' => $weekDay, 'doctor_schedule_id' => $doctorScheduleId])
            ->one();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => 'success',
            'content' => $this->renderAjax('_ajax-new-doctor-appointment-form', [
                'doctorScheduleItem' => $doctorScheduleItem
            ])
        ];
    }

    public function actionSalary(): string
    {
        $urlStartDate = Yii::$app->request->get('start_date');
        $urlEndDate = Yii::$app->request->get('end_date');
        $data['start_date'] = !empty($urlStartDate) ? $urlStartDate : date('Y-m-01');
        $data['end_date'] = !empty($urlEndDate) ? $urlEndDate : date('Y-m-t');

        $salary = EmployeeSalary::find()->where([
            'and',
            ['user_id' => Yii::$app->user->identity->id],
            ['>=', 'created_at', $data['start_date'] . ' 00:00:00'],
            ['<=', 'created_at', $data['end_date'] . ' 23:59:59']
        ])->sum('employee_earnings');

        return $this->render('salary', [
            'data' => $data,
            'salary' => is_null($salary) ? 0 : $salary,
            'todayStatistics' => User::getCurrentStatistics()
        ]);
    }
}
