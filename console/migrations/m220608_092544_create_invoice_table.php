<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 */
class m220608_092544_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer(),
            'doctor_id' => $this->integer(),
            'reception_id' => $this->integer(),
            'teeth' => $this->string(),
            'comments' => $this->string(),
        ]);

        $this->addForeignKey('invoice-patient_id','invoice','patient_id','patient','id');
        $this->addForeignKey('invoice-doctor_id','invoice','doctor_id','user','id');
        $this->addForeignKey('invoice-reception_id','invoice','reception_id','reception','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('invoice-patient_id','invoice');
        $this->dropForeignKey('invoice-doctor_id','invoice');
        $this->dropForeignKey('invoice-reception_id','invoice');

        $this->dropTable('{{%invoice}}');
    }
}
