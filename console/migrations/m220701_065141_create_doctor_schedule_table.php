<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_schedule}}`.
 */
class m220701_065141_create_doctor_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_schedule}}', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'date_from' => $this->date()->notNull(),
            'date_to' => $this->date()->null(),
            'current' => $this->boolean()->defaultValue(0),
        ]);

        $this->addForeignKey('fk-doctor_schedule-doctor_id','doctor_schedule','doctor_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%doctor_schedule}}');
    }
}
