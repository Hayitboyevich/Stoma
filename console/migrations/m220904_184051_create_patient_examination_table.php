<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%patient_examination}}`.
 */
class m220904_184051_create_patient_examination_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_examination}}', [
            'id' => $this->primaryKey(),
            'visit_id' => $this->integer()->null(),
            'patient_id' => $this->integer()->notNull(),
            'doctor_id' => $this->integer()->notNull(),
            'complaints' => $this->text()->null(),
            'anamnesis' => $this->text()->null(),
            'weight' => $this->string()->null(),
            'height' => $this->string()->null(),
            'head_circumference' => $this->string()->null(),
            'temperature' => $this->string()->null(),
            'inspection_data' => $this->text()->null(),
            'diagnosis' => $this->text()->null(),
            'recommendations' => $this->text()->null(),
            'datetime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk-patient_examination-visit_id','patient_examination','visit_id','reception','id');
        $this->addForeignKey('fk-patient_examination-patient_id','patient_examination','patient_id','patient','id');
        $this->addForeignKey('fk-patient_examination-doctor_id','patient_examination','doctor_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%patient_examination}}');
    }
}
