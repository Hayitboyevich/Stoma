<?php

namespace backend\controllers;

use common\models\constants\UserRole;
use common\models\DoctorSchedule;
use common\models\EmployeeSalary;
use common\models\LoginForm;
use common\models\Report;
use common\models\ReportInvoice;
use common\models\SmsGateway;
use common\models\Transaction;
use common\models\User;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ErrorAction;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'paid-patients', 'error', 'balance-patients'],
                        'allow' => true,
                        'roles' => ['?', '@']
                    ],
                    ['actions' => ['login'], 'allow' => true, 'roles' => ['?']],
                    ['actions' => ['logout'], 'allow' => true, 'roles' => ['@']],
                    ['actions' => ['reset-password'], 'allow' => true, 'roles' => ['?']],
                    ['actions' => ['schedule'], 'allow' => true, 'roles' => ['site_schedule']],
                    ['actions' => ['send-sms-code'], 'allow' => true, 'roles' => ['?']],
                    [
                        'actions' => [
                            'statistics',
                            'view-earnings',
                            'export',
                            'report',
                            'excel-employee-salary',
                            'excel-all-employee-salary',
                        ],
                        'allow' => true,
                        'roles' => ['view_statistics']
                    ],
                    ['actions' => ['statistics-by-day'], 'allow' => true, 'roles' => ['view_statistics_by_day']],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
                'layout' => 'error-main',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->identity) {
            $this->redirect('site/login');
            return true;
        }

        switch (Yii::$app->user->identity->role) {
            case 'cashier':
                $this->redirect('cashier/patient');
                break;
            case 'doctor':
                $this->redirect('user/records');
                break;
            case 'director':
                $this->redirect('site/statistics');
                break;
            case 'accountant':
                $this->redirect('cashier/stats');
                break;
            case 'technician':
                $this->redirect('user/salary');
                break;
            default:
                $this->redirect('reception/index');
        }

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $loginError = false;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        if (Yii::$app->request->isPost) {
            $loginError = true;
        }

        $model->password = '';

        return $this->render('login', [
            'login_error' => $loginError
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSchedule(): string
    {
        $doctorSchedules = [];
        foreach (DoctorSchedule::WEEKDAYS as $key => $weekday) {
            $doctors = User::find()
                ->alias('u')
                ->select([
                    'u.id as doctor_id',
                    'u.assistant_id',
                    'u.firstname',
                    'u.lastname',
                    'ds.id AS doctor_schedule_id',
                    'ds.current',
                    'dsi.weekday',
                    'dsi.time_from',
                    'dsi.time_to',
                    'u.role'
                ])
                ->leftJoin(
                    'doctor_schedule AS ds',
                    'ds.doctor_id = u.id AND ds.current = ' . DoctorSchedule::ACTIVE_SCHEDULE
                )
                ->leftJoin('doctor_schedule_item AS dsi', 'dsi.doctor_schedule_id = ds.id')
                ->with([
                    'assistant' => function ($query) {
                        $query->select(['id', 'firstname', 'lastname']);
                    }
                ])
                ->where(
                    [
                        'u.role' => UserRole::ROLE_DOCTOR,
                        'u.status' => User::STATUS_ACTIVE,
                        'u.work_status' => User::WORK_STATUS_AVAILABLE
                    ]
                )
                ->andWhere(['like', 'dsi.weekday', $key])
                ->asArray()
                ->all();

            foreach ($doctors as $doctor) {
                $doctorSchedules[$weekday][] = $doctor;
            }
        }

        return $this->render('schedule', [
            'doctorSchedules' => $doctorSchedules
        ]);
    }

    public function actionResetPassword(): string
    {
        return $this->render('reset-password');
    }

    public function actionSendSmsCode(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
        $username = User::getOnlyNumbers($post['login']);
        if (empty($username) || !($user = User::findOne(['username' => $username]))) {
            return ['status' => 'fail', 'message' => 'not_found'];
        }
        $code = rand(10000, 999999);
        $smsGateway = new SmsGateway();
        $smsGateway->recipient = $user->phone;
        $smsGateway->text = SmsGateway::formatSms([
            'template' => 'reset_code',
            'data' => [
                'code' => $code,
            ]
        ]);

        $response = $smsGateway->sendSms();
        $parsed_response = json_decode($response['response'], true);
        if (empty($parsed_response['error'])) {
            $user->reset_password_code = $code;
            $user->reset_code_expire = date("Y/m/d H:i:s", strtotime("+30 minutes"));
            if ($user->save()) {
                $output = ['status' => 'success', 'message' => 'sms_sent'];
            } else {
                $output = ['status' => 'fail', 'message' => print_r($user->errors, true)];
            }
        } else {
            $output = [
                'status' => 'fail',
                'message' => $parsed_response['error']['code'] . ' ' . $parsed_response['error']['message']
            ];
        }
        return $output;
    }

    /**
     * @return string
     */
    public function actionStatistics(): string
    {
        $startDate = Yii::$app->request->get('start_date', date('Y-m-01'));
        $endDate = Yii::$app->request->get('end_date', date('Y-m-t'));

        $users = User::find()
            ->select(['id', 'firstname', 'lastname', 'role'])
            ->with([
                'earnings' => function ($query) use ($startDate, $endDate) {
                    $query->andWhere([
                        'AND',
                        ['>=', 'created_at', $startDate . ' 00:00:00'],
                        ['<=', 'created_at', $endDate . ' 23:59:59']
                    ]);
                }
            ])
            ->where(
                [
                    'role' => [UserRole::ROLE_DOCTOR, UserRole::ROLE_ASSISTANT],
                    'status' => User::STATUS_ACTIVE,
                    'work_status' => User::WORK_STATUS_AVAILABLE
                ]
            )
            ->orderBy(['lastname' => SORT_ASC, 'firstname' => SORT_ASC])
            ->all();

        $report = new ReportInvoice(['start_date' => $startDate, 'end_date' => $endDate]);

        $totalSalary = User::getAllTotalEarnings($startDate, $endDate);

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'visits' => $report->getVisits(),
            'invoices_total' => $report->getInvoicesTotal(),
            'paid_invoices_total' => $report->getPaidInvoicesTotal(),
            'total_sms_sent' => $report->getSentSmsCount(),
            'total_balance' => $report->getTotalBalance(),
            'total_salary' => $totalSalary,
            'total_debt' => $report->getDebt(),
            'total_profit' => $report->getProfit(),
            'average_check' => $report->getAverageCheck(),
            'total_salary_percent' => $report->getInvoicesTotal() > 0
                ? round($totalSalary / $report->getInvoicesTotal() * 100)
                : 0,
        ];

        return $this->render('statistics', [
            'data' => $data,
            'users' => $users
        ]);
    }

    /**
     * @return string
     */
    public function actionExport(): string
    {
        $this->layout = 'excel_blank';

        $startDate = Yii::$app->request->getQueryParam('start_date', date('Y-m-01'));
        $endDate = Yii::$app->request->getQueryParam('end_date', date('Y-m-t'));

        $reports = Report::find()
            ->with(['patient', 'doctor', 'assistant', 'invoice', 'priceListItem', 'priceList'])
            ->andWhere([
                'and',
                ['>=', 'created_at', $startDate . ' 00:00:00'],
                ['<=', 'created_at', $endDate . ' 23:59:59']
            ])
            ->all();

        return $this->render('export', [
            'reports' => $reports,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }

    /**
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function actionViewEarnings(int $userId, string $startDate, string $endDate): string
    {
        $user = User::findOne($userId);

        $employeeSalary = $user->getEarningsByDate($startDate, $endDate)->all();

        return $this->render('view-earnings', [
            'employeeSalary' => $employeeSalary,
            'user' => $user,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function actionExcelEmployeeSalary(int $userId, string $startDate, string $endDate): string
    {
        $this->layout = 'excel_blank';

        $employeeSalary = EmployeeSalary::find()
            ->where([
                'and',
                ['user_id' => $userId],
                ['>=', 'created_at', $startDate . ' 00:00:00'],
                ['<=', 'created_at', $endDate . ' 23:59:59']
            ])
            ->orderBy(['visit_time' => SORT_DESC])
            ->all();

        return $this->render('export-employee-salary', [
            'employeeSalary' => $employeeSalary
        ]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function actionExcelAllEmployeeSalary(string $startDate, string $endDate): string
    {
        $this->layout = 'excel_blank';

        $users = User::find()
            ->select(['id', 'firstname', 'lastname', 'role'])
            ->with([
                'earnings' => function ($query) use ($startDate, $endDate) {
                    $query->andWhere([
                        'AND',
                        ['>=', 'created_at', $startDate . ' 00:00:00'],
                        ['<=', 'created_at', $endDate . ' 23:59:59']
                    ]);
                }
            ])
            ->where(
                [
                    'role' => [UserRole::ROLE_DOCTOR, UserRole::ROLE_ASSISTANT],
                    'status' => User::STATUS_ACTIVE,
                    'work_status' => User::WORK_STATUS_AVAILABLE
                ]
            )
            ->orderBy(['lastname' => SORT_ASC, 'firstname' => SORT_ASC])
            ->all();

        return $this->render('export-all-employee-salary', [
            'users' => $users,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function actionReport(string $startDate, string $endDate): string
    {
        $reports = Report::find()
            ->with('patient', 'doctor', 'assistant', 'invoice', 'priceListItem')
            ->where([
                'and',
                ['>=', 'created_at', $startDate . ' 00:00:00'],
                ['<=', 'created_at', $endDate . ' 23:59:59']
            ])
            ->orderBy(['visit_time' => SORT_DESC])
            ->all();

        return $this->render('report', [
            'reports' => $reports,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    public function actionStatisticsByDay(): string
    {
        $startDate = date('Y-m-d') . ' 00:00:00';
        $endDate = date('Y-m-d') . ' 23:59:59';

        $report = new ReportInvoice(['start_date' => $startDate, 'end_date' => $endDate]);
        $data = [
            'invoices_total' => $report->getInvoicesTotal(),
            'invoices_count' => $report->getInvoicesCount(),
            'pay_total' => $report->getPayTotal(),
            'total_debt' => $report->getDebt(),
            'insurance_invoices_count' => $report->getInsuranceInvoicesCount(),
            'insurance_invoices_total' => $report->getInsuranceInvoicesTotal()
        ];

        return $this->render('statistics-by-day', [
            'data' => $data
        ]);
    }

    public function actionPaidPatients(string $startDate, string $endDate): string
    {
        $invoices = Report::find()
            ->select([
                'report.invoice_id as invoice_id',
                'report.paid_sum',
                'report.created_at',
                'report.visit_time',
                'report.patient_id',
                'report.doctor_id',
            ])
            ->with([
                'patient' => function ($query) {
                    $query->select(['id', 'concat(lastname, " ", firstname) as p_full_name']);
                },
                'doctor' => function ($query) {
                    $query->select(['id', 'concat(lastname, " ", firstname) as d_full_name']);
                }
            ])
            ->innerJoinWith([
                'invoice' => function ($query) use ($startDate, $endDate) {
                    $query->select(['invoice.id'])->andWhere([
                        'and',
                        ['>=', 'invoice.created_at', $startDate . ' 00:00:00'],
                        ['<=', 'invoice.created_at', $endDate . ' 23:59:59']
                    ]);
                }
            ])->asArray()->all();

        return $this->render('paid-patients', [
            'invoices' => $invoices
        ]);
    }

    public function actionBalancePatients()
    {
        $get = Yii::$app->request->get();

        $query = Transaction::find()
            ->select([
                'patient.id as patient_id',
                'concat(patient.lastname, " ", patient.firstname) as full_name',
                'balance' => new Expression(
                    'SUM(CASE WHEN type = :addMoney THEN amount ELSE 0 END) -
            SUM(CASE WHEN type IN (:pay, :withdrawMoney) THEN amount ELSE 0 END)',
                    [
                        ':addMoney' => Transaction::TYPE_ADD_MONEY,
                        ':pay' => Transaction::TYPE_PAY,
                        ':withdrawMoney' => Transaction::TYPE_WITHDRAW_MONEY,
                    ]
                ),
                'last_updated_date' => new Expression('MAX(transaction.created_at)')
            ])
            ->innerJoin('patient', 'patient.id = transaction.patient_id')
            ->groupBy('patient_id')
            ->having(['>', 'balance', 0])
            ->asArray();

        $total_rows = $query->count();
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

        $data['data'] = $query
            ->offset($data['offset'])
            ->limit($data['per_page'])
            ->all();

        $data['last_row_number'] = $data['offset'] + $data['per_page'] * $data['page'];

        if ($data['last_row_number'] > $data['total_rows']) {
            $data['last_row_number'] = $data['total_rows'];
        }

        $data['show_pagination'] = $data['total_rows'] > $data['per_page'] || array_key_exists('per_page', $get);

        return $this->render('balance-patients', [
            'data' => $data
        ]);
    }
}
