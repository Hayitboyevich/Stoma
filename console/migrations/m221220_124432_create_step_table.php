<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%step}}`.
 */
class m221220_124432_create_step_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%step}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->bigInteger(),
            'step_1' => $this->smallInteger(),
            'step_2' => $this->smallInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%step}}');
    }
}
