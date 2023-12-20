<?php

use yii\db\Migration;

/**
 * Class m230502_061733_alter_table_employee_salary
 */
class m230502_061733_alter_table_employee_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('employee_salary', 'paid_sum', $this->decimal(10, 2)
            ->notNull()
            ->defaultValue(0)
            ->after('price_with_discount'));

        $this->alterColumn('employee_salary', 'employee_earnings', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230502_061733_alter_table_employee_salary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230502_061733_alter_table_employee_salary cannot be reverted.\n";

        return false;
    }
    */
}
