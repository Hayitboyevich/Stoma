<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%callback}}`.
 */
class m220710_083846_drop_callback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%callback}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%callback}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
