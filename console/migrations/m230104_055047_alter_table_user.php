<?php

use yii\db\Migration;

/**
 * Class m230104_055047_alter_table_user
 */
class m230104_055047_alter_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'assistant_id', $this->integer()->after('media_id'));

        $this->addForeignKey(
            'fk-user-assistant_id',
            'user',
            'assistant_id',
            'user',
            'id'
        );

        $this->createIndex(
            'idx-user-assistant_id',
            'user',
            'assistant_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230104_055047_alter_table_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230104_055047_alter_table_user cannot be reverted.\n";

        return false;
    }
    */
}
