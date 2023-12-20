<?php

use yii\db\Migration;

/**
 * Class m230117_095103_alter_table_price_list_item
 */
class m230117_095103_alter_table_price_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('price_list_item', 'remains');
        $this->dropColumn('price_list_item', 'profitability');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230117_095103_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230117_095103_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }
    */
}
