<?php

use yii\db\Migration;

/**
 * Class m230502_065216_alter_table_invoice
 */
class m230502_065216_alter_table_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice', 'closed_at', $this->dateTime());
        $this->addColumn('invoice', 'insurance_name', $this->string(255)->after('invoice_number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230502_065216_alter_table_invoice cannot be reverted.\n";

        return false;
    }
}
