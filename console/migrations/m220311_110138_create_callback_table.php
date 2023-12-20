<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%callback}}`.
 */
class m220311_110138_create_callback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%callback}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer(),
            'status' => $this->boolean()->defaultValue(0),
            'comments' => $this->text(),
            'created_at' => $this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%callback}}');
    }
}
