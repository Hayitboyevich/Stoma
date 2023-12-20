<?php

use yii\db\Migration;

/**
 * Class m220629_112835_drop_tables
 */
class m220629_112835_drop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('add_money_to_account');
        $this->dropTable('patient_spending');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220629_112835_drop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220629_112835_drop_tables cannot be reverted.\n";

        return false;
    }
    */
}
