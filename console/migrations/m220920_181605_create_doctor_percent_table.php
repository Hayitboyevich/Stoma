<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_percent}}`.
 */
class m220920_181605_create_doctor_percent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_percent}}', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'price_list_id' => $this->integer()->notNull(),
            'doctor_percent' => $this->integer()->notNull(),
            'assistant_percent' => $this->integer(),
        ]);

        $this->addForeignKey('fk-doctor_percent-doctor_id','doctor_percent','doctor_id','user','id');
        $this->addForeignKey('fk-doctor_percent-price_list_id','doctor_percent','price_list_id','price_list','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-doctor_percent-doctor_id','doctor_percent');
        $this->dropForeignKey('fk-doctor_percent-price_list_id','doctor_percent');
        $this->dropTable('{{%doctor_percent}}');
    }
}
