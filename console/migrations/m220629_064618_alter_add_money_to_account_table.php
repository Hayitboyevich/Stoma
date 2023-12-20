<?php

use yii\db\Migration;

/**
 * Class m220629_064618_alter_add_money_to_account_table
 */
class m220629_064618_alter_add_money_to_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-add-money-to-account-patient_id','add_money_to_account','patient_id','patient','id');
        $this->addForeignKey('fk-add-money-to-account-user_id','add_money_to_account','user_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-add-money-to-account-patient_id','add_money_to_account');
        $this->dropForeignKey('fk-add-money-to-account-user_id','add_money_to_account');
        echo "m220629_064618_alter_add_money_to_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220629_064618_alter_add_money_to_account_table cannot be reverted.\n";

        return false;
    }
    */
}
