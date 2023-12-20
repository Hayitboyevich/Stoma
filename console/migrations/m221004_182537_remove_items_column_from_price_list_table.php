<?php

use yii\db\Migration;

/**
 * Class m221004_182537_remove_items_column_from_price_list_table
 */
class m221004_182537_remove_items_column_from_price_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropColumn('price_list','items');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221004_182537_remove_items_column_from_price_list_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221004_182537_remove_items_column_from_price_list_table cannot be reverted.\n";

        return false;
    }
    */
}
