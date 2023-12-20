<?php

use yii\db\Migration;

/**
 * Class m220613_061324_alter_invoice_table
 */
class m220613_061324_alter_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // ALTER TABLE `invoice` ADD `invoice_number` varchar(255) NOT NULL
        $this->addColumn('invoice', 'invoice_number', $this->string()->notNull()->comment("Счет №"));

        // ALTER TABLE `invoice` ADD `created_at` NOT NULL
        $this->addColumn('invoice', 'created_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment("Дата счета"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('invoice','invoice_number');
        $this->dropColumn('invoice','created_at');

        echo "m220613_061324_alter_invoice_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220613_061324_alter_invoice_table cannot be reverted.\n";

        return false;
    }
    */
}
