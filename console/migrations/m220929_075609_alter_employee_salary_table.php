<?php

use yii\db\Migration;

/**
 * Class m220929_075609_alter_employee_salary_table
 */
class m220929_075609_alter_employee_salary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee_salary','teeth_amount',$this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220929_075609_alter_employee_salary_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220929_075609_alter_employee_salary_table cannot be reverted.\n";

        return false;
    }
    */
}
