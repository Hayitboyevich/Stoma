<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%old_patient}}`.
 */
class m221018_200448_add_note_column_to_old_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('old_patient','note',$this->string()->comment('ПРИМЕЧАНИЕ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
