<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%patient}}`.
 */
class m221018_193908_add_old_id_column_to_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('patient','old_id',$this->integer()->null()->comment('ID from old database table'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
