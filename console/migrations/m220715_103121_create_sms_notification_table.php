<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sms_notification}}`.
 */
class m220715_103121_create_sms_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sms_notification}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'user_id' => $this->integer()->null(),
            'patient_id' => $this->integer()->null(),
            'response' => $this->text()->null(),
            'status' => $this->string()->null(),
            'phone' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-sms_notification-user_id','sms_notification','user_id','user','id');
        $this->addForeignKey('fk-sms_notification-patient_id','sms_notification','patient_id','patient','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sms_notification}}');
    }
}
