<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inline_message}}`.
 */
class m220311_083644_create_inline_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%inline_message}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer()->null(),
            'message_data' => $this->text(),
            'created_at' => $this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%inline_message}}');
    }
}
