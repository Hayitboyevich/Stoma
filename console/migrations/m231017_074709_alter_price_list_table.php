<?php

use yii\db\Migration;

/**
 * Class m231017_074709_alter_price_list_table
 */
class m231017_074709_alter_price_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('price_list', 'status', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231017_074709_alter_price_list_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231017_074709_alter_price_list_table cannot be reverted.\n";

        return false;
    }
    */
}
