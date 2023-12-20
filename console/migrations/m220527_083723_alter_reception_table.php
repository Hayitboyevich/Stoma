<?php

use yii\db\Migration;

/**
 * Class m220527_083723_alter_reception_table
 */
class m220527_083723_alter_reception_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropColumn('reception','reception_time');
        $this->addColumn('reception','record_date',$this->date());
        $this->addColumn('reception','record_time_from',$this->time());
        $this->addColumn('reception','record_time_to',$this->time());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('reception','reception_time',$this->integer()->notNull()->comment('Время приема'));
        $this->dropColumn('reception','record_date');
        $this->dropColumn('reception','record_time_from');
        $this->dropColumn('reception','record_time_to');
        echo "m220527_083723_alter_reception_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220527_083723_alter_reception_table cannot be reverted.\n";

        return false;
    }
    */
}
