<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice_refund}}`.
 */
class m221019_091112_create_invoice_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_refund}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer(),
            'requested_user_id' => $this->integer(),
            'approved_or_declined_user_id' => $this->integer(),
            'approved_or_declined_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'approved_or_declined_comment' => $this->string()->null(),
            'status' => $this->string()->defaultValue('new'),
            'comments' => $this->string()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%invoice_refund}}');
    }
}
