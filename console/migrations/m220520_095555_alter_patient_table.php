<?php

use yii\db\Migration;

/**
 * Class m220520_095555_alter_patient_table
 */
class m220520_095555_alter_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('patient','dob',$this->date()->null());
        $this->addColumn('patient','vip',$this->boolean()->defaultValue(false));
        $this->addColumn('patient','last_visited',$this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('patient','dob');
        $this->dropColumn('patient','vip');
        $this->dropColumn('patient','last_visited');
        echo "m220520_095555_alter_patient_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220520_095555_alter_patient_table cannot be reverted.\n";

        return false;
    }
    */
}
