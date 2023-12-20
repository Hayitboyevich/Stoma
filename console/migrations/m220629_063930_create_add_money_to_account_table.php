<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%add_money_to_account}}`.
 */
class m220629_063930_create_add_money_to_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%add_money_to_account}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'payment_method' => $this->string()->notNull(),
            'amount' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%add_money_to_account}}');
    }
}
