<?php

namespace console\controllers;

use common\models\DoctorPercent;
use common\models\EmployeeSalary;
use common\models\Invoice;
use yii\console\Controller;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class TestController extends Controller
{
    function actionEmployeeSalary()
    {
        $invoice = Invoice::findOne(3);
        foreach ($invoice->invoiceServices as $is) {
            $dp = DoctorPercent::findOne(
                ['user_id' => $invoice->doctor_id, 'price_list_id' => $is->priceListItem->price_list_id]
            );
            $es = new EmployeeSalary();
            $es->invoice_id = $invoice->id;
            $es->invoice_total = $invoice->getInvoiceTotal();
            $es->reception_id = $invoice->reception_id;
            $es->visit_time = "{$invoice->reception->record_date} {$invoice->reception->record_time_from}";
            $es->user_id = $invoice->doctor_id;
            $es->patient_id = $invoice->patient_id;
            $es->price_list_id = $is->priceListItem->priceList->id;
            $es->cat_title = $is->priceListItem->priceList->section;
            $es->price_list_item_id = $is->priceListItem->id;
            $es->sub_cat_title = $is->priceListItem->name;
            $es->cat_percent = $dp->percent;
            $es->sub_cat_price = $is->price;
            $es->sub_cat_amount = $is->amount;
            $es->employee_earnings = $is->price * $is->amount * $dp->percent / 100;
            $es->save();
        }
        die();
    }
}
