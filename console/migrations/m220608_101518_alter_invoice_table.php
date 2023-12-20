<?php

use yii\db\Migration;

/**
 * Class m220608_101518_alter_invoice_table
 */
class m220608_101518_alter_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice','preliminary',$this->boolean()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('invoice','preliminary');
        echo "m220608_101518_alter_invoice_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220608_101518_alter_invoice_table cannot be reverted.\n";

        return false;
    }
    */
}
