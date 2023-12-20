<?php

use yii\db\Migration;

/**
 * Class m230908_100034_alter_table_price_list_item
 */
class m230908_100034_alter_table_price_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('price_list_item', 'status', $this->smallInteger()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230908_100034_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230908_100034_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }
    */
}
