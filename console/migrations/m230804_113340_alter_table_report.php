<?php

use yii\db\Migration;

/**
 * Class m230804_113340_alter_table_report
 */
class m230804_113340_alter_table_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('report', 'debt', $this->decimal(10, 2)->defaultValue(0)->comment('Долг')->after('paid_sum'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230804_113340_alter_table_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230804_113340_alter_table_report cannot be reverted.\n";

        return false;
    }
    */
}
