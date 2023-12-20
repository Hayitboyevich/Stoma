<?php

use yii\db\Migration;

/**
 * Class m220929_072545_alter_invoice_services_table
 */
class m220929_072545_alter_invoice_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice_services','title',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220929_072545_alter_invoice_services_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220929_072545_alter_invoice_services_table cannot be reverted.\n";

        return false;
    }
    */
}
