<?php

use yii\db\Migration;

/**
 * Class m231015_144359_alter_price_list_item_table
 */
class m231015_144359_alter_price_list_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('price_list_item', 'is_group', $this->boolean()->defaultValue(false)->after('parent_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231015_144359_alter_price_list_item_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231015_144359_alter_price_list_item_table cannot be reverted.\n";

        return false;
    }
    */
}
