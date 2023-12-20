<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m221018_194403_add_old_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','old_id',$this->integer()->null()->comment('ID from old database table'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
