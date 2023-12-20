<?php

use yii\db\Migration;

/**
 * Class m230519_101911_create_table_doctor_item_percent
 */
class m230519_101911_create_table_doctor_item_percent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('doctor_item_percent', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'price_list_item_id' => $this->integer()->notNull(),
            'percent' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-doctor_item_percent-doctor_id',
            'doctor_item_percent',
            'doctor_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-doctor_item_percent-price_list_item_id',
            'doctor_item_percent',
            'price_list_item_id',
            'price_list_item',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-doctor_item_percent-doctor_id',
            'doctor_item_percent',
            'doctor_id'
        );

        $this->createIndex(
            'idx-doctor_item_percent-price_list_item_id',
            'doctor_item_percent',
            'price_list_item_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230519_101911_create_table_doctor_item_percent cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230519_101911_create_table_doctor_item_percent cannot be reverted.\n";

        return false;
    }
    */
}
