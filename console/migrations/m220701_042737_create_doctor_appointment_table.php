<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_appointment}}`.
 */
class m220701_042737_create_doctor_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_appointment}}', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'time_from' => $this->time()->notNull(),
            'time_to' => $this->time()->notNull(),
        ]);

        $this->addForeignKey('fk-doctor_appointment-doctor_id','doctor_appointment','doctor_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%doctor_appointment}}');
    }
}
