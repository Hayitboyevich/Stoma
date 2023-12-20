<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_salary}}`.
 */
class m220926_105123_create_employee_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_salary}}', [
            'id'                    => $this->primaryKey(),
            'invoice_id'            => $this->integer(),
            'invoice_total'         => $this->integer(),
            'reception_id'          => $this->integer(),
            'visit_time'            => $this->timestamp(),
            'user_id'               => $this->integer(),
            'patient_id'            => $this->integer(),
            'price_list_id'         => $this->integer(),
            'cat_title'             => $this->string(),
            'price_list_item_id'    => $this->integer(),
            'sub_cat_title'         => $this->string(),
            'cat_percent'           => $this->integer(),
            'sub_cat_price'         => $this->integer(),
            'sub_cat_amount'        => $this->integer(),
            'employee_earnings'     => $this->integer(),
            'created_at'            => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-employee_salary-invoice_id','employee_salary','invoice_id','invoice','id');
        $this->addForeignKey('fk-employee_salary-reception_id','employee_salary','reception_id','reception','id');
        $this->addForeignKey('fk-employee_salary-user_id','employee_salary','user_id','user','id');
        $this->addForeignKey('fk-employee_salary-patient_id','employee_salary','patient_id','patient','id');
        $this->addForeignKey('fk-employee_salary-price_list_id','employee_salary','price_list_id','price_list','id');
        $this->addForeignKey('fk-employee_salary-price_list_item_id','employee_salary','price_list_item_id','price_list_item','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_salary}}');
    }
}
