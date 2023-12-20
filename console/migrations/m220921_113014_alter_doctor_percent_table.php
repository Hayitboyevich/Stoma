<?php

use yii\db\Migration;

/**
 * Class m220921_113014_alter_doctor_percent_table
 */
class m220921_113014_alter_doctor_percent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('doctor_percent','doctor_percent');
        $this->dropColumn('doctor_percent','assistant_percent');
        $this->renameColumn('doctor_percent','doctor_id','user_id');
        $this->addColumn('doctor_percent','percent',$this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220921_113014_alter_doctor_percent_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220921_113014_alter_doctor_percent_table cannot be reverted.\n";

        return false;
    }
    */
}
