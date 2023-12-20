<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%config}}`.
 */
class m220727_074538_create_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%config}}', [
            'key' => $this->string()->notNull()->unique(),
            'value' => $this->string()->null(),
        ]);

        $this->addPrimaryKey('config_pk','config','key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
