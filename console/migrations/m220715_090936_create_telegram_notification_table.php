<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_notification}}`.
 */
class m220715_090936_create_telegram_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%telegram_notification}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'user_id' => $this->integer()->null(),
            'patient_id' => $this->integer()->null(),
            'response' => $this->text()->null(),
            'status' => $this->string()->null(),
            'chat_id' => $this->bigInteger()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-telegram_notification-user_id','telegram_notification','user_id','user','id');
        $this->addForeignKey('fk-telegram_notification-patient_id','telegram_notification','patient_id','patient','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%telegram_notification}}');
    }
}
