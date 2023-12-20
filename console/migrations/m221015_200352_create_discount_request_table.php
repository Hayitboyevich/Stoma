<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discount_request}}`.
 */
class m221015_200352_create_discount_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%discount_request}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'patient_id' => $this->integer(),
            'discount' => $this->integer(),
            'status' => $this->string(255)->defaultValue('new'),
            'approved_user_id' => $this->integer()->null(),
            'approved_time' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-discount_request-user_id','discount_request','user_id','user','id');
        $this->addForeignKey('fk-discount_request-patient_id','discount_request','patient_id','patient','id');
        $this->addForeignKey('fk-discount_request-approved_user_id','discount_request','approved_user_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%discount_request}}');
    }
}
