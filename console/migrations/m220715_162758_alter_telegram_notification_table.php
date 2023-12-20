<?php

use yii\db\Migration;

/**
 * Class m220715_162758_alter_telegram_notification_table
 */
class m220715_162758_alter_telegram_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('telegram_notification','object_type',$this->string()->null());
        $this->addColumn('telegram_notification','object_id',$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220715_162758_alter_telegram_notification_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220715_162758_alter_telegram_notification_table cannot be reverted.\n";

        return false;
    }
    */
}
