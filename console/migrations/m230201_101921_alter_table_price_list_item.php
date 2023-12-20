<?php

use yii\db\Migration;

/**
 * Class m230201_101921_alter_table_price_list_item
 */
class m230201_101921_alter_table_price_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('price_list_item', 'consumable', $this->integer()->comment('Расходный материал')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230201_101921_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230201_101921_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }
    */
}
