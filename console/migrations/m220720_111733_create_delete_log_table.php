<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delete_log}}`.
 */
class m220720_111733_create_delete_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%delete_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(),
            'object_type' => $this->string(),
            'object_id' => $this->integer(),
            'object_data' => $this->text(),
            'deleted_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%delete_log}}');
    }
}
