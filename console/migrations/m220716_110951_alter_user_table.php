<?php

use yii\db\Migration;

/**
 * Class m220716_110951_alter_user_table
 */
class m220716_110951_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','reset_password_code',$this->string()->null());
        $this->addColumn('user','reset_code_expire',$this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220716_110951_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220716_110951_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
