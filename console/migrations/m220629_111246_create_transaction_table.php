<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m220629_111246_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'payment_method' => $this->string()->notNull(),
            'amount' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'invoice_id' => $this->integer()->null(),
            'invoice_number' => $this->string()->null(),
            'type' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-transaction-patient_id','transaction','patient_id','patient','id');
        $this->addForeignKey('fk-transaction-user_id','transaction','user_id','user','id');
        $this->addForeignKey('fk-transaction-invoice_id','transaction','invoice_id','invoice','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaction}}');
    }
}
