<?php

use yii\db\Migration;

/**
 * Class m230123_064341_alter_table_transaction
 */
class m230123_064341_alter_table_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'is_transfer', $this->boolean()->defaultValue(false)->after('type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'is_transfer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230123_064341_alter_table_transaction cannot be reverted.\n";

        return false;
    }
    */
}
