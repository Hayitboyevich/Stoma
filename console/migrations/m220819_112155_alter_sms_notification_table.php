<?php

use yii\db\Migration;

/**
 * Class m220819_112155_alter_sms_notification_table
 */
class m220819_112155_alter_sms_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sms_notification','request_data',$this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220819_112155_alter_sms_notification_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220819_112155_alter_sms_notification_table cannot be reverted.\n";

        return false;
    }
    */
}
