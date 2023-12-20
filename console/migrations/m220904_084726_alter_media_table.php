<?php

use yii\db\Migration;

/**
 * Class m220904_084726_alter_media_table
 */
class m220904_084726_alter_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('media','title',$this->string()->null());
        $this->addColumn('media','description',$this->text()->null());
        $this->addColumn('media','object_type',$this->string()->null());
        $this->addColumn('media','object_id',$this->integer()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220904_084726_alter_media_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220904_084726_alter_media_table cannot be reverted.\n";

        return false;
    }
    */
}
