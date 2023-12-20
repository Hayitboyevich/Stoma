<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%doctor_appointment}}`.
 */
class m220701_064853_drop_doctor_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%doctor_appointment}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%doctor_appointment}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
