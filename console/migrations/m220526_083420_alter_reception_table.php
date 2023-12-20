<?php

use yii\db\Migration;

/**
 * Class m220526_083420_alter_reception_table
 */
class m220526_083420_alter_reception_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reception','category_id',$this->integer());
        $this->addForeignKey('fk-reception-category_id','reception','category_id','price_list','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('reception','category_id');
        $this->dropForeignKey('fk-reception-category_id','reception');

        echo "m220526_083420_alter_reception_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220526_083420_alter_reception_table cannot be reverted.\n";

        return false;
    }
    */
}
