<?php

use yii\db\Migration;

/**
 * Class m220530_110626_alter_user_table
 */
class m220530_110626_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','work_status',$this->string()->defaultValue('available')->comment('Работает?'));
        $this->addColumn('user','work_start_date',$this->date()->comment('ДАТА НАЧАЛО РАБОТЫ'));
        $this->addColumn('user','dob',$this->date()->comment('ДАТА РОЖДЕНИЯ'));
        $this->addColumn('user','passport',$this->string()->comment('Серия и номер пасспорта'));
        $this->addColumn('user','passport_issued',$this->string()->comment('Пасспорт выдан'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','work_status');
        $this->dropColumn('user','work_start_date');
        $this->dropColumn('user','dob');
        $this->dropColumn('user','passport');
        $this->dropColumn('user','passport_issued');

        echo "m220530_110626_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220530_110626_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
