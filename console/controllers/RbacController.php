<?php

namespace console\controllers;

use common\models\constants\UserRole;
use common\models\rbac\ViewInvoiceDetails;
use common\models\rbac\ViewPatient;
use common\models\rbac\ViewRecordDetails;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class RbacController extends Controller
{
    public function getPermissions(): array
    {
        return [
            [
                'name' => 'appointment_request_handle',
                'description' => 'Назначать на себя обработку заявку на запись из телеграм бота',
                'rule' => []
            ],
            [
                'name' => 'appointment_request_index',
                'description' => 'Смотреть список всех заявок на запись из телеграм бота',
                'rule' => []
            ],
            ['name' => 'auth_item_role', 'description' => 'Смотреть список доступных ролей в системе', 'rule' => []],
            ['name' => 'auth_item_update_role', 'description' => 'Редактировать параметры роля', 'rule' => []],
            ['name' => 'config_create', 'description' => 'Создавать новые настройки в системе', 'rule' => []],
            ['name' => 'config_index', 'description' => 'Смотреть список доступных настроек', 'rule' => []],
            ['name' => 'config_update', 'description' => 'Менять значения системных настроек', 'rule' => []],
            ['name' => 'delete_log_index', 'description' => 'Смотреть записи журнала удаленных данных', 'rule' => []],
            ['name' => 'invoice_ajax_create', 'description' => 'Создавать новые инвойсы', 'rule' => []],
            ['name' => 'invoice_pay', 'description' => 'Оплачивать инвойсы', 'rule' => []],
            [
                'name' => 'invoice_details',
                'description' => 'Смотреть детали инвойса',
                'rule' => [
                    'instance' => new ViewInvoiceDetails()
                ]
            ],
            ['name' => 'patient_add_money_to_account', 'description' => 'Пополнять счета пациентов', 'rule' => []],
            ['name' => 'patient_ajax_create', 'description' => 'Добавлять новых пациентов в систему', 'rule' => []],
            ['name' => 'patient_ajax_delete', 'description' => 'Удалять пациентов из системы', 'rule' => []],
            [
                'name' => 'patient_ajax_update',
                'description' => 'Редактировать данные пациента',
                'rule' => [
                    'instance' => new ViewPatient()
                ]
            ],
            ['name' => 'patient_index', 'description' => 'Смотреть список всех пациентов', 'rule' => []],
            ['name' => 'price_list_manage', 'description' => 'Вносить изминения в прайс лист', 'rule' => []],
            ['name' => 'reception_cancel_record', 'description' => 'Отменять записи на прием к врачу', 'rule' => []],
            ['name' => 'reception_index', 'description' => 'Смотреть список всех записей на прием', 'rule' => []],
            [
                'name' => 'reception_view_record_details',
                'description' => 'Смотреть детали записи',
                'rule' => [
                    'instance' => new ViewRecordDetails()
                ]
            ],
            ['name' => 'reception_new_record', 'description' => 'Добавлять записи на прием к врачу', 'rule' => []],
            ['name' => 'reception_remove_record', 'description' => 'Удалять записи на прием к врачу', 'rule' => []],
            [
                'name' => 'reception_send_sms_notification',
                'description' => 'Отправлять смс напоминание пациенту о записи на прием к врачу',
                'rule' => []
            ],
            [
                'name' => 'reception_send_telegram_notification',
                'description' => 'Отправлять напоминание в телеграм пациента о записи на прием к врачу',
                'rule' => []
            ],
            ['name' => 'site_schedule', 'description' => 'Смотреть расписание всех врачей', 'rule' => []],
            ['name' => 'sms_notification_index', 'description' => 'Смотреть историю отправленных смс', 'rule' => []],
            [
                'name' => 'telegram_notification_index',
                'description' => 'Смотреть историю отправленных телеграм сообщений',
                'rule' => []
            ],
            ['name' => 'user_ajax_create', 'description' => 'Добавлять новых пользователей в систему', 'rule' => []],
            ['name' => 'user_ajax_delete', 'description' => 'Удалять пользователей из системы', 'rule' => []],
            ['name' => 'user_ajax_update', 'description' => 'Редактировать данные пользователей', 'rule' => []],
            ['name' => 'user_doctor_schedule_add', 'description' => 'Заполнять расписанию врача', 'rule' => []],
            ['name' => 'user_index', 'description' => 'Смотреть список всех пользователей', 'rule' => []],
            ['name' => 'assign_discount', 'description' => 'Назначать скидку пациентам', 'rule' => []],
            ['name' => 'view_statistics', 'description' => 'Смотреть статистику', 'rule' => []],
            ['name' => 'view_statistics_by_day', 'description' => 'Смотреть статистику по дням', 'rule' => []],
            ['name' => 'update_salary_percents', 'description' => 'Обновлять проценты зарплаты врачей', 'rule' => []],
            ['name' => 'cashier_stats', 'description' => 'Статистика кассира', 'rule' => []],
            ['name' => 'view_patient_discount', 'description' => 'Видеть скидки пациентов', 'rule' => []],
            ['name' => 'new_examination_create', 'description' => 'Добавлять данные осмотра (педиатрия)', 'rule' => []],
            ['name' => 'upload_patient_files', 'description' => 'Загружать файлы пациентов', 'rule' => []],
            ['name' => 'invoice_cancel', 'description' => 'Отменять инвойсы до оплаты', 'rule' => []],
            ['name' => 'invoice_insurance', 'description' => 'Создавать инвойсы страховыми компаниями', 'rule' => []],
            ['name' => 'invoice_enumeration', 'description' => 'Создавать инвойсы на перечисление', 'rule' => []],
            ['name' => 'invoice_debt', 'description' => 'Создавать инвойсы на долги', 'rule' => []],
            ['name' => 'request_discount', 'description' => 'Создавать заявки на скидку', 'rule' => []],
            ['name' => 'approve_discount', 'description' => 'Утверждение скидки', 'rule' => []],
            ['name' => 'request_discount_index', 'description' => 'Смотреть список заявок на скидку', 'rule' => []],
            ['name' => 'approve_decline_refund', 'description' => 'Утверждение возврата денег', 'rule' => []],
            [
                'name' => 'invoice_refund_request_create',
                'description' => 'Создание запроса на возврат денег',
                'rule' => []
            ],
            ['name' => 'manage_user_permissions', 'description' => 'Управлять правами пользователей', 'rule' => []],
            ['name' => 'view_salary', 'description' => 'посмотреть зарплату', 'rule' => []],
            ['name' => 'view_report', 'description' => 'посмотреть отчет', 'rule' => []],
            ['name' => 'view_patient_balance', 'description' => 'просмотреть баланс пациента', 'rule' => []],
            ['name' => 'transfer_money', 'description' => 'Перевести деньги', 'rule' => []],
            ['name' => 'delete_patient_file', 'description' => 'Удалить файл пациента', 'rule' => []],
            ['name' => 'withdraw_money', 'description' => 'Снять деньги со счета пациента', 'rule' => []],
            [
                'name' => 'technician_price_list_manage',
                'description' => 'Вносить изминения в прайс лист техника',
                'rule' => []
            ],
            ['name' => 'cashier_patient_index', 'description' => 'Смотреть список сегодняшние пациентов', 'rule' => []],
            ['name' => 'patient_debts', 'description' => 'Посмотреть долгов пациентов', 'rule' => []],
            ['name' => 'view_insurance_invoices', 'description' => 'Посмотреть страховых инвойсы', 'rule' => []],
            ['name' => 'view_enumeration_invoices', 'description' => 'Посмотреть инвойсы на перечисление', 'rule' => []],
            ['name' => 'pay_insurance_invoice', 'description' => 'Оплатить страховых инвойсы'],
            ['name' => 'pay_enumeration_invoice', 'description' => 'Оплатить инвойсы на перечисление'],
            ['name' => 'invoice_delete', 'description' => 'Удалить инвойс']
        ];
    }

    public function getRoles(): array
    {
        return array(
            array(
                'name' => UserRole::ROLE_RECEPTION,
                'description' => 'Приемная',
                'permissions' => array(
                    'appointment_request_handle',
                    'appointment_request_index',
                    'patient_ajax_create',
                    'patient_ajax_delete',
                    'patient_ajax_update',
                    'patient_index',
                    'reception_cancel_record',
                    'reception_index',
                    'reception_new_record',
                    'reception_remove_record',
                    'reception_send_sms_notification',
                    'reception_send_telegram_notification',
                    'reception_view_record_details',
                    'site_schedule',
                    'view_patient_discount',
                    'invoice_refund_request_create',
                    'upload_patient_files'
                )
            ),
            array(
                'name' => UserRole::ROLE_DOCTOR,
                'description' => 'Врач',
                'permissions' => array(
                    'invoice_ajax_create',
                    'user_doctor_schedule_add',
                    'reception_new_record',
                    'reception_view_record_details',
                    'patient_index',
                    'patient_ajax_update',
                    'invoice_details',
                    'upload_patient_files',
                    'invoice_cancel',
                    'request_discount',
                    'invoice_refund_request_create',
                    'view_salary',
                    'new_examination_create',
                    'delete_patient_file'
                ),
            ),
            array(
                'name' => UserRole::ROLE_ASSISTANT,
                'description' => 'Ассистент',
                'permissions' => array(),
            ),
            array(
                'name' => UserRole::ROLE_DIRECTOR,
                'description' => 'Директор',
                'permissions' => array(
                    'appointment_request_handle',
                    'appointment_request_index',
                    'auth_item_role',
                    'auth_item_update_role',
                    'config_create',
                    'config_index',
                    'config_update',
                    'delete_log_index',
                    'invoice_ajax_create',
                    'invoice_pay',
                    'invoice_details',
                    'patient_add_money_to_account',
                    'patient_ajax_create',
                    'patient_ajax_delete',
                    'patient_ajax_update',
                    'patient_index',
                    'price_list_manage',
                    'reception_cancel_record',
                    'reception_index',
                    'reception_view_record_details',
                    'reception_new_record',
                    'reception_remove_record',
                    'reception_send_sms_notification',
                    'reception_send_telegram_notification',
                    'site_schedule',
                    'sms_notification_index',
                    'telegram_notification_index',
                    'user_ajax_create',
                    'user_ajax_delete',
                    'user_ajax_update',
                    'user_doctor_schedule_add',
                    'user_index',
                    'assign_discount',
                    'view_statistics',
                    'update_salary_percents',
                    'cashier_stats',
                    'view_patient_discount',
                    'upload_patient_files',
                    'invoice_cancel',
                    'invoice_insurance',
                    'invoice_enumeration',
                    'invoice_debt',
                    'approve_discount',
                    'approve_decline_refund',
                    'invoice_refund_request_create',
                    'manage_user_permissions',
                    'delete_patient_file',
                    'technician_price_list_manage',
                    'patient_debts',
                    'view_statistics_by_day',
                    'view_insurance_invoices',
                    'pay_insurance_invoice',
                    'request_discount_index',
                    'invoice_delete',
                    'view_enumeration_invoices',
                    'pay_enumeration_invoice'
                ),
            ),
            array(
                'name' => UserRole::ROLE_ADMIN,
                'description' => 'Администратор',
                'permissions' => array(
                    'appointment_request_handle',
                    'appointment_request_index',
                    'auth_item_role',
                    'auth_item_update_role',
                    'config_create',
                    'config_index',
                    'config_update',
                    'delete_log_index',
                    'invoice_ajax_create',
                    'invoice_pay',
                    'invoice_details',
                    'patient_add_money_to_account',
                    'patient_ajax_create',
                    'patient_ajax_delete',
                    'patient_ajax_update',
                    'patient_index',
                    'price_list_manage',
                    'reception_cancel_record',
                    'reception_index',
                    'reception_view_record_details',
                    'reception_new_record',
                    'reception_remove_record',
                    'reception_send_sms_notification',
                    'reception_send_telegram_notification',
                    'site_schedule',
                    'sms_notification_index',
                    'telegram_notification_index',
                    'user_ajax_create',
                    'user_ajax_delete',
                    'user_ajax_update',
                    'user_doctor_schedule_add',
                    'user_index',
                    'assign_discount',
                    'view_statistics',
                    'update_salary_percents',
                    'cashier_stats',
                    'view_patient_discount',
                    'upload_patient_files',
                    'invoice_cancel',
                    'invoice_insurance',
                    'invoice_enumeration',
                    'invoice_debt',
                    'invoice_refund_request_create',
                    'manage_user_permissions',
                    'delete_patient_file',
                    'technician_price_list_manage',
                    'patient_debts',
                    'view_statistics_by_day',
                    'view_insurance_invoices',
                    'pay_insurance_invoice',
                    'invoice_delete',
                    'view_enumeration_invoices',
                    'pay_enumeration_invoice'
                )
            ),
            array(
                'name' => UserRole::ROLE_CASHIER,
                'description' => 'Кассир',
                'permissions' => array(
                    'patient_index',
                    'cashier_patient_index',
                    'invoice_pay',
                    'patient_add_money_to_account',
                    'invoice_details',
                    'cashier_stats',
                    'view_patient_discount',
                    'invoice_cancel',
                    'invoice_insurance',
                    'invoice_enumeration',
                    'invoice_debt',
                    'request_discount',
                    'invoice_refund_request_create',
                    'view_report',
                    'view_patient_balance',
                    'transfer_money',
                    'withdraw_money',
                    'patient_debts',
                    'view_insurance_invoices',
                    'pay_insurance_invoice',
                    'view_enumeration_invoices',
                    'pay_enumeration_invoice'
                )
            ),
            array(
                'name' => UserRole::ROLE_ACCOUNTANT,
                'description' => 'Бухгалтер',
                'permissions' => array(
                    'cashier_patient_index',
                    'transfer_money',
                    'cashier_stats',
                    'view_insurance_invoices',
                    'pay_insurance_invoice',
                    'view_enumeration_invoices',
                    'pay_enumeration_invoice'
                )
            ),
            array(
                'name' => UserRole::ROLE_TECHNICIAN,
                'description' => 'Техник',
                'permissions' => array(
                    'view_salary'
                )
            ),
            array(
                'name' => UserRole::ROLE_BUYER,
                'description' => 'Закупщик',
                'permissions' => array()
            ),
            array(
                'name' => UserRole::ROLE_API,
                'description' => 'Апи',
                'permissions' => array()
            )
        );
    }

    public function actionInit()
    {
        $permissions = $this->getPermissions();

        $roles = $this->getRoles();

        $auth = Yii::$app->authManager;
        echo "Remove all permissions \r\n";
        $auth->removeAllPermissions();
        echo "Done \r\n";
        echo "Remove all Rules \r\n";
        $auth->removeAllRules();
        echo "Done \r\n";

        foreach ($permissions as $permission) {
            $auth = Yii::$app->authManager;
            echo "Create permission {$permission['name']} \r\n";
            $createPermission = $auth->createPermission($permission['name']);
            $createPermission->description = $permission['description'];
            if (!empty($permission['rule'])) {
                $auth->add($permission['rule']['instance']);
                $createPermission->ruleName = $permission['rule']['instance']->name;
            }
            $auth->add($createPermission);
            echo "Done \r\n";
        }

        foreach ($roles as $role) {
            $realRole = $auth->getRole($role['name']);
            if (!$realRole) {
                echo "Create role {$role['name']} \r\n";
                $realRole = $auth->createRole($role['name']);
                $realRole->description = $role['description'];
                $auth->add($realRole);
                echo "Done \r\n";
            }


            if (!empty($role['permissions'])) {
                foreach ($role['permissions'] as $role_permission) {
                    $this_permission = $auth->getPermission($role_permission);
                    echo "Assignee permission '{$this_permission->name}' to role '{$realRole->name}'\r\n";
                    $auth->addChild($realRole, $this_permission);
                    echo "Done\r\n";
                }
            }
        }
        echo "OK\r\n";
        return true;
    }

    public function actionGetAllControllerActions()
    {
        $controllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@backend/controllers'), ['recursive' => true]);
        $actions = [];
        $out = [];
        foreach ($controllers as $controller) {
            $contents = file_get_contents($controller);
            $controllerId = Inflector::camel2id(substr(basename($controller), 0, -14));
            preg_match_all('/function action(\w+?)\(/', $contents, $result);
            foreach ($result[1] as $action) {
                $actionId = Inflector::camel2id($action);
                $route = str_replace('-', '_', $controllerId . '_' . $actionId);
                $out[$controllerId][] = $actionId;
                $actions[$route] = "['name' => '{$route}','description' => '','rule' => []],";
            }
        }
        asort($actions);
        print_r($out);
        //file_put_contents('all_actions.txt',print_r($out,true));
        file_put_contents('all_actions.txt', implode(PHP_EOL, $actions));
        die();
    }

    public function actionUpdate(): bool
    {
        $permissions = $this->getPermissions();

        $roles = $this->getRoles();

        foreach ($permissions as $permission) {
            $auth = Yii::$app->authManager;
            $createPermission = $auth->createPermission($permission['name']);
            $createPermission->description = $permission['description'];

            if (!$auth->getPermission($permission['name'])) {
                $auth->add($createPermission);
                echo "Done \r\n";
            }
        }

        foreach ($roles as $role) {
            $realRole = $auth->getRole($role['name']);

            if (!$realRole) {
                echo "Create role {$role['name']} \r\n";
                $realRole = $auth->createRole($role['name']);
                $realRole->description = $role['description'];
                $auth->add($realRole);
                echo "Done \r\n";
            }

            if (!empty($role['permissions'])) {
                foreach ($role['permissions'] as $role_permission) {
                    $this_permission = $auth->getPermission($role_permission);
                    if (!$auth->hasChild($realRole, $this_permission)) {
                        $auth->addChild($realRole, $this_permission);
                    }
                }
            }
        }
        echo "UPDATE\r\n";
        return true;
    }
}
