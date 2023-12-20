<?php

use yii\db\Migration;

/**
 * Class m221219_071152_alter_table_appointment_request
 */
class m221219_071152_alter_table_appointment_request extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('appointment_request', 'source', $this->string(55)->after('phone'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('appointment_request', 'source');
    }
}
