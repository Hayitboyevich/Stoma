<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%patient}}`.
 */
class m220311_101035_add_chat_id_column_to_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('patient','chat_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('patient','chat_id');
    }
}
