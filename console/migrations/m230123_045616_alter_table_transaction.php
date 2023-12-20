<?php

use yii\db\Migration;

/**
 * Class m230123_045616_alter_table_transaction
 */
class m230123_045616_alter_table_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'recipient_patient_id', $this->integer()->after('patient_id'));

        $this->addForeignKey('fk-transaction-recipient_patient_id', 'transaction', 'patient_id', 'patient', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transaction-recipient_patient_id', 'transaction');
        $this->dropColumn('transaction', 'recipient_patient_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230123_045616_alter_table_transaction cannot be reverted.\n";

        return false;
    }
    */
}
