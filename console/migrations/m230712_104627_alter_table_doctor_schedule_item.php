<?php

use yii\db\Migration;

/**
 * Class m230712_104627_alter_table_doctor_schedule_item
 */
class m230712_104627_alter_table_doctor_schedule_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('doctor_schedule_item', 'break_time_from');
        $this->dropColumn('doctor_schedule_item', 'break_time_to');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230712_104627_alter_table_doctor_schedule_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230712_104627_alter_table_doctor_schedule_item cannot be reverted.\n";

        return false;
    }
    */
}
