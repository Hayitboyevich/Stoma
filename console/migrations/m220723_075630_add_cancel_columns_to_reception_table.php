<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reception}}`.
 */
class m220723_075630_add_cancel_columns_to_reception_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reception','canceled',$this->tinyInteger()->defaultValue(0));
        $this->addColumn('reception','cancel_reason',$this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
