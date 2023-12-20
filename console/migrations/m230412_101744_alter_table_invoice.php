<?php

use yii\db\Migration;

/**
 * Class m230412_101744_alter_table_invoice
 */
class m230412_101744_alter_table_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice', 'type', $this->smallInteger()->notNull()->after('assistant_id'));
        $this->dropColumn('invoice', 'status');
        $this->addColumn('invoice', 'status', $this->smallInteger()->defaultValue(1)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230412_101744_alter_table_invoice cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230412_101744_alter_table_invoice cannot be reverted.\n";

        return false;
    }
    */
}
