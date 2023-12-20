<?php

use yii\db\Migration;

/**
 * Class m220601_095423_alter_patient_table
 */
class m220601_095423_alter_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('patient','gender',$this->string());
        $this->addColumn('patient','media_id',$this->integer()->null());
        $this->addColumn('patient','created_at',$this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('patient','discount',$this->integer()->null());
        $this->addColumn('patient','doctor_id',$this->integer()->null());

        $this->addForeignKey('fk-patient-media_id','patient','media_id','media','id');
        $this->addForeignKey('fk-patient-doctor_id','patient','doctor_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-patient-media_id','patient');
        $this->dropForeignKey('fk-patient-doctor_id','patient');

        $this->dropColumn('patient','gender');
        $this->dropColumn('patient','media_id');
        $this->dropColumn('patient','created_at');
        $this->dropColumn('patient','discount');


        echo "m220601_095423_alter_patient_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220601_095423_alter_patient_table cannot be reverted.\n";

        return false;
    }
    */
}
