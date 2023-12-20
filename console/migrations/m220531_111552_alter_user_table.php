<?php

use yii\db\Migration;

/**
 * Class m220531_111552_alter_user_table
 */
class m220531_111552_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','media_id',$this->integer()->null()->comment('Аватарка'));
        $this->addForeignKey('fk-user-media_id','user','media_id','media','id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','media_id');
        $this->dropForeignKey('fk-user-media_id','user');
        echo "m220531_111552_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220531_111552_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
