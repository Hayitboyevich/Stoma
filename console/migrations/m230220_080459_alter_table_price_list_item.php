<?php

use yii\db\Migration;

/**
 * Class m230220_080459_alter_table_price_list_item
 */
class m230220_080459_alter_table_price_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('price_list_item', 'technician_price_list_id', $this->integer()->after('price_list_id'));

        $this->createIndex(
            'idx-price_list_item-technician_price_list_id',
            'price_list_item',
            'technician_price_list_id'
        );

        $this->addForeignKey(
            'fk-price_list_item-technician_price_list_id',
            'price_list_item',
            'technician_price_list_id',
            'technician_price_list',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-price_list_item-technician_price_list_id', 'price_list_item');
        $this->dropIndex('idx-price_list_item-technician_price_list_id', 'price_list_item');
        $this->dropColumn('price_list_item', 'technician_price_list_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230220_080459_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }
    */
}
