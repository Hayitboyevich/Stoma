<?php

use yii\db\Migration;

/**
 * Class m220928_101104_alter_doctor_salary_table
 */
class m220928_101104_alter_doctor_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee_salary','patient_name',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220928_101104_alter_doctor_salary_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220928_101104_alter_doctor_salary_table cannot be reverted.\n";

        return false;
    }
    */
}
