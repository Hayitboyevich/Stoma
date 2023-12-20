<?php

namespace console\controllers;

use common\models\OldEmployee;
use common\models\OldPatient;
use common\models\OldPrice;
use common\models\Patient;
use common\models\PriceList;
use common\models\PriceListItem;
use common\models\User;
use Yii;
use yii\console\Controller;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class ImportController extends Controller
{

    public function actionPatients()
    {
        $file = __DIR__ . '/import-files/stomaservice_import.xlsx';
        echo $file;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        echo $spreadsheet->getSheetCount();
        $sheet = $spreadsheet->getSheet(0);
        $res = $sheet->toArray();
        foreach ($res as $index => $patient) {
            if ($index == 0) {
                continue;
            }
            $old_patient = new OldPatient();
            $old_patient->id = $patient[0];
            $old_patient->card_number = $patient[1];
            $old_patient->first_visit = !empty($patient[2]) && $patient[2] != '-' ? date(
                "Y-m-d H:i:s",
                strtotime($patient[2])
            ) : null;
            $old_patient->last_name = $patient[3];
            $old_patient->first_name = $patient[4];
            $old_patient->patronymic = $patient[5];
            $old_patient->gender = $patient[6];
            $old_patient->dob = !empty($patient[7]) && $patient[7] != '-' ? date(
                "Y-m-d",
                strtotime($patient[7])
            ) : null;
            $old_patient->phone = $patient[8];
            $old_patient->phone_home = $patient[9];
            $old_patient->phone_work = $patient[10];
            $old_patient->email = $patient[11];
            $old_patient->discount = $patient[12];
            $old_patient->home_address = $patient[13];
            $old_patient->note = $patient[14];
            $old_patient->doctor_id = $patient[15];
            $old_patient->hygienist_id = $patient[16];
            $old_patient->source = $patient[17];
            $old_patient->recommended_patient = $patient[18];
            $old_patient->recommended_user = $patient[19];
            $old_patient->who_were_recommended = $patient[20];
            $old_patient->patient_status = $patient[21];
            $old_patient->debt = $patient[22];
            $old_patient->credit = $patient[23];
            $old_patient->recommendations_amount = $patient[24];
            if ($old_patient->save(false)) {
                echo "Ok\r\n";
            } else {
                print_r($old_patient->errors);
                echo "\r\n";
            }
        }
        die();
    }

    public function actionEmployees()
    {
        $file = __DIR__ . '/import-files/import.xlsx';
        echo $file;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        echo $spreadsheet->getSheetCount();
        $sheet = $spreadsheet->getSheet(6);
        $res = $sheet->toArray();
        foreach ($res as $index => $employee) {
            if ($index == 0) {
                continue;
            }
            $old_employee = new OldEmployee();
            $old_employee->id = $employee[0];
            $old_employee->last_name = $employee[1];
            $old_employee->first_name = $employee[2];
            $old_employee->patronymic = $employee[3];
            $old_employee->dob = !empty($employee[4]) && $employee[4] != '-' ? date(
                'Y-m-d',
                strtotime($employee[4])
            ) : '';
            $old_employee->status = $employee[5];
            $old_employee->work_start_date = !empty($employee[6]) ? date('Y-m-d', strtotime($employee[6])) : '';
            $old_employee->work_end_date = !empty($employee[7]) ? date('Y-m-d', strtotime($employee[7])) : '';
            $old_employee->email = $employee[8];
            $old_employee->initials = $employee[9];
            $old_employee->phone = $employee[10];
            $old_employee->phone_home = $employee[11];
            $old_employee->phone_work = $employee[12];

            if ($old_employee->save(false)) {
                echo "Ok\r\n";
            } else {
                print_r($old_employee->errors);
                echo "\r\n";
            }
        }
        die();
    }

    public function actionMigratePatients()
    {
        /**@var $old_patient OldPatient */
        $old_patients = OldPatient::find()->all();
        if (!empty($old_patients)) {
            foreach ($old_patients as $old_patient) {
                if (Patient::findOne(['old_id' => $old_patient->id])) {
                    continue;
                }
                $patient = new Patient();
                $patient->firstname = static::cyr2lat($old_patient->first_name);
                $patient->lastname = static::cyr2lat($old_patient->last_name);
                $patient->phone = $old_patient->phone;
                $patient->dob = $old_patient->dob;
                $patient->gender = static::getGender($old_patient->gender);
                $patient->discount = $old_patient->discount;
                $patient->old_id = $old_patient->id;
                if ($patient->save(false)) {
                    echo "{$patient->lastname} {$patient->firstname} \r\n";
                } else {
                    print_r($patient->errors) . "\r\n";
                }
            }
        }
    }

    public function actionPatientUpd()
    {
        /**@var $old_patient OldPatient */
        $old_patients = OldPatient::find()->all();
        if (!empty($old_patients)) {
            foreach ($old_patients as $old_patient) {
                if ($pa = Patient::findOne(['old_id' => $old_patient->id])) {
                    $pa->firstname = $old_patient->first_name;
                    $pa->lastname = $old_patient->last_name;
                    $pa->save(false);
                    if ($pa->save(false)) {
                        echo "{$pa->lastname} {$pa->firstname} \r\n";
                    } else {
                        print_r($pa->errors) . "\r\n";
                    }
                }
            }
        }
    }

    public function actionMigrateUsers()
    {
        /**@var $old_user OldEmployee */
        $old_users = OldEmployee::find()->all();
        if (!empty($old_users)) {
            foreach ($old_users as $old_user) {
                if (User::findOne(['old_id' => $old_user->id])) {
                    continue;
                }
                $user = new User();
                $user->lastname = static::cyr2lat($old_user->last_name);
                $user->firstname = static::cyr2lat($old_user->first_name);
                $user->phone = User::getOnlyNumbers($old_user->phone);
                $user->username = User::getOnlyNumbers($old_user->phone);
                $user->auth_key = \Yii::$app->security->generateRandomString();
                $user->password_hash = \Yii::$app->security->generatePasswordHash($old_user->email . $user->phone);
                $user->email = $old_user->email == '-' ? "{$user->phone}@stomaservice.uz" : $old_user->email;
                $user->status = 10;
                $user->role = 'doctor';
                $user->work_status = static::getWorkStatus($old_user->status);
                $user->work_start_date = $old_user->work_start_date;
                $user->dob = $old_user->dob;

                $user->old_id = $old_user->id;
                if ($user->save(false)) {
                    echo "{$user->lastname} {$user->firstname} \r\n";
                } else {
                    print_r($user->errors) . "\r\n";
                }
            }
        }
    }

    public static function getWorkStatus($status): string
    {
        if ($status === 'Работает') {
            return 'available';
        }

        if ($status === 'В отпуске') {
            return 'vacation';
        }

        if ($status === 'Уволен') {
            return 'fired';
        }

        return '';
    }

    public static function getGender($gender)
    {
        if ($gender == 'М') {
            return 'M';
        }

        if ($gender == 'Ж') {
            return 'F';
        }

        return '';
    }

    public static function cyr2lat($st)
    {
        $st = mb_strtolower($st, "utf-8");
        $st = str_replace([
            '?',
            '!',
            '.',
            ',',
            ':',
            ';',
            '*',
            '(',
            ')',
            '{',
            '}',
            '[',
            ']',
            '%',
            '#',
            '№',
            '@',
            '$',
            '^',
            '-',
            '+',
            '/',
            '\\',
            '=',
            '|',
            '"',
            '\'',
            'а',
            'б',
            'в',
            'г',
            'д',
            'е',
            'ё',
            'з',
            'и',
            'й',
            'к',
            'л',
            'м',
            'н',
            'о',
            'п',
            'р',
            'с',
            'т',
            'у',
            'ф',
            'х',
            'ъ',
            'ы',
            'э',
            ' ',
            'ж',
            'ц',
            'ч',
            'ш',
            'щ',
            'ь',
            'ю',
            'я'
        ],
            [
            '_',
            '_',
            '.',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            '_',
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'e',
            'z',
            'i',
            'y',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'j',
            'i',
            'e',
            '_',
            'zh',
            'ts',
            'ch',
            'sh',
            'shch',
            '',
            'yu',
            'ya'
        ], $st);
        $st = preg_replace("/[^a-z0-9_.]/", "", $st);
        $st = trim($st, '_');

        $prev_st = '';
        do {
            $prev_st = $st;
            $st = preg_replace("/_[a-z0-9]_/", "_", $st);
        } while ($st != $prev_st);

        $st = preg_replace("/_{2,}/", "_", $st);
        return ucfirst($st);
    }

    public function actionPrices()
    {
        $file = __DIR__ . '/import-files/import.xlsx';
        echo $file;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        echo $spreadsheet->getSheetCount();
        $sheet = $spreadsheet->getSheet(5);
        $res = $sheet->toArray();
        foreach ($res as $index => $price) {
            if ($index == 0) {
                continue;
            }
            $old_price = new OldPrice();
            $old_price->section_code = $price[0];
            $old_price->section = $price[1];
            $old_price->group = $price[2];
            $old_price->position_code = $price[3];
            $old_price->position = $price[4];
            $old_price->price = $price[5];


            if ($old_price->save(false)) {
                echo "Ok\r\n";
            } else {
                print_r($old_price->errors);
                echo "\r\n";
            }
        }
        die();
    }

    public function actionMigratePrices()
    {
        /**@var $price OldPrice */
        /**@var $price_item OldPrice */
        $prices = OldPrice::find()->select('section')->groupBy('section')->all();
        foreach ($prices as $price) {
            $model = PriceList::findOne(['section' => trim($price->section)]);
            if (!$model) {
                $model = new PriceList();
                $model->section = $price->section;
                if ($model->save()) {
                    echo "{$model->section} - OK \r\n";
                } else {
                    print_r($model->errors);
                }
            }
        }

        $prices_item = OldPrice::find()->all();
        foreach ($prices_item as $price_item) {
            $item_model = PriceListItem::findOne(['name' => trim($price_item->position)]);
            if (!$item_model) {
                $item_model = new PriceListItem();
                $item_model->price_list_id = self::PriceListIdFromName($price_item->section);
                $item_model->name = $price_item->position;
                $item_model->price = $price_item->price;
                if ($item_model->save()) {
                    echo "{$item_model->name} - OK \r\n";
                } else {
                    print_r($item_model->errors);
                }
            }
        }
    }

    public static function PriceListIdFromName($name)
    {
        $model = PriceList::findOne(['section' => trim($name)]);
        return $model ? $model->id : 0;
    }
}
