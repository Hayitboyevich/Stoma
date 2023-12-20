<?php

use yii\db\Migration;

/**
 * Class m230216_095707_alter_table_transaction_table
 */
class m230216_095707_alter_table_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'transaction',
            'cancel_reason',
            $this->string()->after('invoice_number')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'cancel_reason');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230216_095707_alter_table_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
