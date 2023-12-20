<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%patient}}`.
 */
class m220301_073724_create_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient}}', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string()->null(),
            'lastname' => $this->string()->null(),
            'phone' => $this->string()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%patient}}');
    }
}
