<?php

use yii\db\Migration;

/**
 * Class m220624_070439_alter_invoice_table
 */
class m220624_070439_alter_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('invoice','teeth');
        $this->addColumn('invoice','assistant_id',$this->integer()->null());

        $this->addForeignKey('invoice-assistant_id','invoice','assistant_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220624_070439_alter_invoice_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220624_070439_alter_invoice_table cannot be reverted.\n";

        return false;
    }
    */
}
