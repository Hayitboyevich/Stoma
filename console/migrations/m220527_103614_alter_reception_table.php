<?php

use yii\db\Migration;

/**
 * Class m220527_103614_alter_reception_table
 */
class m220527_103614_alter_reception_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reception','sms',$this->string()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reception','sms');
        echo "m220527_103614_alter_reception_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_103614_alter_reception_table cannot be reverted.\n";

        return false;
    }
    */
}
