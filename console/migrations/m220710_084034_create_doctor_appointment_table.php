<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_appointment}}`.
 */
class m220710_084034_create_doctor_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_appointment}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->bigInteger(),
            'status' => $this->tinyInteger()->defaultValue(0),
            'operator_id' => $this->integer()->null(),
            'patient_id' => $this->integer()->null(),
            'first_name' => $this->string()->null(),
            'last_name' => $this->string()->null(),
            'phone' => $this->string()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-doctor_appointment-operator_id','doctor_appointment','operator_id','user','id');
        $this->addForeignKey('fk-doctor_appointment-patient_id','doctor_appointment','patient_id','patient','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%doctor_appointment}}');
    }
}
