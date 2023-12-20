<?php

use yii\db\Migration;

/**
 * Class m220710_102721_rename_doctor_appointment_table
 */
class m220710_102721_rename_doctor_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->renameTable('doctor_appointment','appointment_request');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220710_102721_rename_doctor_appointment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220710_102721_rename_doctor_appointment_table cannot be reverted.\n";

        return false;
    }
    */
}
