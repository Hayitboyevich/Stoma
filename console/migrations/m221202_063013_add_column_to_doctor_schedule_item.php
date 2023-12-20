<?php

use yii\db\Migration;

/**
 * Class m221202_063013_add_column_to_doctor_schedule_item
 */
class m221202_063013_add_column_to_doctor_schedule_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('doctor_schedule_item', 'break_time_from', $this->time()->notNull());
        $this->addColumn('doctor_schedule_item', 'break_time_to', $this->time()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('doctor_schedule_item', 'break_time_from');
        $this->dropColumn('doctor_schedule_item', 'break_time_to');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221202_063013_add_column_to_doctor_schedule_item cannot be reverted.\n";

        return false;
    }
    */
}
