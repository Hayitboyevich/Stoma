<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%old_patient}}`.
 */
class m221018_200141_add_email_column_to_old_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('old_patient','email',$this->string()->comment('EMAIL'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
