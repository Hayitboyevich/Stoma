<?php

use yii\db\Migration;

/**
 * Class m230510_073740_alter_table_report
 */
class m230510_073740_alter_table_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('report', 'utilities', $this->float());
        $this->alterColumn('report', 'amortization', $this->float());
        $this->alterColumn('report', 'marketing', $this->float());
        $this->alterColumn('report', 'other_expenses', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230510_073740_alter_table_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230510_073740_alter_table_report cannot be reverted.\n";

        return false;
    }
    */
}
