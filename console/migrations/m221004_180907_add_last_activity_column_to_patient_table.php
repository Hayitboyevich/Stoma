<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%patient}}`.
 */
class m221004_180907_add_last_activity_column_to_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('patient','last_activity',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
