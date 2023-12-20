<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_schedule_item}}`.
 */
class m220704_074051_create_doctor_schedule_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_schedule_item}}', [
            'id' => $this->primaryKey(),
            'doctor_schedule_id' => $this->integer()->notNull(),
            'weekday' => $this->string()->notNull(),
            'time_from' => $this->time()->notNull(),
            'time_to' => $this->time()->notNull(),
        ]);

        $this->addForeignKey('fk-doctor_schedule_item-doctor_schedule_id','doctor_schedule_item','doctor_schedule_id','doctor_schedule','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%doctor_schedule_item}}');
    }
}
