<?php

use yii\db\Migration;

/**
 * Class m221221_054654_alter_appointment_request_table
 */
class m221221_054654_alter_appointment_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('appointment_request', 'parent_phone', $this->string()->after('phone'));
        $this->addColumn('appointment_request', 'parent_last_name', $this->string()->after('phone'));
        $this->addColumn('appointment_request', 'parent_first_name', $this->string()->after('phone'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221221_054654_alter_appointment_request_table cannot be reverted.\n";

        return false;
    }
}
