<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reception}}`.
 */
class m220919_104257_add_state_column_to_reception_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reception','state',$this->string()->defaultValue('scheduled'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reception','state');
    }
}
