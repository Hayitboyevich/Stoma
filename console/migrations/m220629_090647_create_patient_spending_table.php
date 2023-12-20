<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%patient_spending}}`.
 */
class m220629_090647_create_patient_spending_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_spending}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'invoice_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'invoice_total' => $this->integer(),
            'discount' => $this->integer(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-patient_spending-patient_id','patient_spending','patient_id','patient','id');
        $this->addForeignKey('fk-patient_spending-user_id','patient_spending','user_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%patient_spending}}');
    }
}
