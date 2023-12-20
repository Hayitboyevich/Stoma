<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reception}}`.
 */
class m220721_054844_add_duration_column_to_reception_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reception','duration',$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
