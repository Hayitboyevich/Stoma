<?php

use yii\db\Migration;

/**
 * Class m230502_093530_alter_table_report
 */
class m230502_093530_alter_table_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('report', 'doctor_earnings', $this->decimal(10, 2));
        $this->alterColumn('report', 'assistant_earnings', $this->decimal(10, 2));
        $this->alterColumn('report', 'technician_earnings', $this->decimal(10, 2));
        $this->alterColumn('report', 'consumable', $this->decimal(10, 2));
        $this->alterColumn('report', 'remains', $this->decimal(10, 2));
        $this->addColumn('report', 'paid_sum', $this->decimal(10, 2)->after('price_with_discount'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230502_093530_alter_table_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230502_093530_alter_table_report cannot be reverted.\n";

        return false;
    }
    */
}
