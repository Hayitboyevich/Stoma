<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_patient}}`.
 */
class m220813_180058_create_doctor_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_patient}}', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'patient_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-doctor_patient-doctor_id','doctor_patient','doctor_id','user','id');
        $this->addForeignKey('fk-doctor_patient-patient_id','doctor_patient','patient_id','patient','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-doctor_patient-doctor_id','doctor_patient');
        $this->dropForeignKey('fk-doctor_patient-patient_id','doctor_patient');
        $this->dropTable('{{%doctor_patient}}');
    }
}
