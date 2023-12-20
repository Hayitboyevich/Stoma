<?php

namespace console\controllers;

use common\models\AppointmentRequest;
use common\models\DeleteLog;
use common\models\DiscountRequest;
use common\models\DoctorCategory;
use common\models\DoctorPatient;
use common\models\DoctorPercent;
use common\models\DoctorSchedule;
use common\models\DoctorScheduleItem;
use common\models\EmployeeSalary;
use common\models\InlineMessage;
use common\models\Invoice;
use common\models\InvoiceRefund;
use common\models\InvoiceServices;
use common\models\Media;
use common\models\Patient;
use common\models\PatientExamination;
use common\models\PriceList;
use common\models\PriceListItem;
use common\models\Reception;
use common\models\SmsNotification;
use common\models\TelegramNotification;
use common\models\TmpUser;
use common\models\Transaction;
use common\models\User;
use PHPMailer\PHPMailer\Exception;
use yii\console\Controller;
use yii\db\StaleObjectException;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class CommonController extends Controller
{

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionClearRecords(){
        /** @var $user User*/
        try{
            echo "Delete all appointment requests\r\n";
            AppointmentRequest::deleteAll();
            echo "OK - Delete all appointment requests\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };


        try{
            echo "Delete all appointment requests\r\n";
            AppointmentRequest::deleteAll();
            echo "OK - Delete all appointment requests\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all delete logs\r\n";
            DeleteLog::deleteAll();
            echo "OK - Delete all delete logs\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all discount requests\r\n";
            DiscountRequest::deleteAll();
            echo "OK - Delete all discount requests\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all doctor categories\r\n";
            DoctorCategory::deleteAll();
            echo "OK - Delete all doctor categories\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all doctor patients\r\n";
            DoctorPatient::deleteAll();
            echo "OK - Delete all doctor patients\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all doctor percents\r\n";
            DoctorPercent::deleteAll();
            echo "OK - Delete all doctor percents\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all doctor schedule items\r\n";
            DoctorScheduleItem::deleteAll();
            echo "OK - Delete all doctor schedule items\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all doctor schedules\r\n";
            DoctorSchedule::deleteAll();
            echo "OK - Delete all doctor schedules\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all employee salary records\r\n";
            EmployeeSalary::deleteAll();
            echo "OK - Delete all employee salary records\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };




        try{
            echo "Delete all telegram inline messages\r\n";
            InlineMessage::deleteAll();
            echo "OK - Delete all telegram inline messages\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all invoice services\r\n";
            InvoiceServices::deleteAll();
            echo "OK - Delete all invoice services\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all invoices\r\n";
            Invoice::deleteAll();
            echo "OK - Delete all invoices\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all invoice refunds\r\n";
            InvoiceRefund::deleteAll();
            echo "OK - Delete all invoice refunds\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };





        try{
            echo "Delete all patient examinations\r\n";
            PatientExamination::deleteAll();
            echo "OK - Delete all patient examinations\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all price list items\r\n";
            PriceListItem::deleteAll();
            echo "OK - Delete all price list items\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all price list\r\n";
            PriceList::deleteAll();
            echo "OK - Delete all price list\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all sms notifications\r\n";
            SmsNotification::deleteAll();
            echo "OK - Delete all sms notifications\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all telegram notifications\r\n";
            TelegramNotification::deleteAll();
            echo "OK - Delete all telegram notifications\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all transactions\r\n";
            Transaction::deleteAll();
            echo "OK - Delete all transactions\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all patients\r\n";
            Patient::deleteAll();
            echo "OK - Delete all patients\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all users\r\n";
            $users = User::find()->all();
            if(!empty($users)){
                foreach($users AS $user){
                    if($user->role == 'admin' || $user->username == '911913458' || $user->lastname == 'Batmanov'){
                        continue;
                    }
                    else{
                        $user->delete();
                    }
                }
            }
            echo "OK - Delete all users\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all records\r\n";
            Reception::deleteAll();
            echo "OK - Delete all records\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

        try{
            echo "Delete all Media\r\n";
            Media::deleteAll();
            echo "OK - Delete all Media\r\n";
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        };

    }
}