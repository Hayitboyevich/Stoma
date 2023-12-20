<?php

use yii\db\Migration;

/**
 * Class m231010_100218_alter_transaction_table
 */
class m231010_100218_alter_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'is_foreign_currency', $this->boolean()->defaultValue(false)->after('amount'));
        $this->addColumn('transaction', 'foreign_currency_rate', $this->decimal(10, 2)->defaultValue(0)->after('is_foreign_currency'));
        $this->addColumn('transaction', 'foreign_currency_amount', $this->decimal(10, 2)->defaultValue(0)->after('foreign_currency_rate'));
        $this->alterColumn('transaction', 'amount', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231010_100218_alter_transaction_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231010_100218_alter_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
