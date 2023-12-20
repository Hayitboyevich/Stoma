<?php

use yii\db\Migration;

/**
 * Class m230203_061705_alter_table_invoice
 */
class m230203_061705_alter_table_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice', 'discount', $this->integer()->defaultValue(0)->after('reception_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('invoice', 'discount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230203_061705_alter_table_invoice cannot be reverted.\n";

        return false;
    }
    */
}
