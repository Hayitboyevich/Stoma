<?php

use yii\db\Migration;

/**
 * Class m221018_200821_alter_dob_column_in_old_patient_table
 */
class m221018_200821_alter_dob_column_in_old_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('old_patient','dob',$this->date()->null()->comment('ДАТА РОЖДЕНИЯ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221018_200821_alter_dob_column_in_old_patient_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221018_200821_alter_dob_column_in_old_patient_table cannot be reverted.\n";

        return false;
    }
    */
}
