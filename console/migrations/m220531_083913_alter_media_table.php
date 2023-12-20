<?php

use yii\db\Migration;

/**
 * Class m220531_083913_alter_media_table
 */
class m220531_083913_alter_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('media','filename',$this->string()->null());
        $this->addColumn('media','file_type',$this->string()->null());
        $this->addColumn('media','path',$this->string()->null());
        $this->addColumn('media','uploaded_at',$this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('media','filename');
        $this->dropColumn('media','file_type');
        $this->dropColumn('media','path');
        $this->dropColumn('media','uploaded_at');

        echo "m220531_083913_alter_media_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220531_083913_alter_media_table cannot be reverted.\n";

        return false;
    }
    */
}
