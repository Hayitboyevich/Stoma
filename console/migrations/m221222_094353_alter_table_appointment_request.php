<?php

use yii\db\Migration;

/**
 * Class m221222_094353_alter_table_appointment_request
 */
class m221222_094353_alter_table_appointment_request extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('appointment_request', 'parent_phone');
        $this->addColumn('appointment_request', 'dob', $this->date()->after('phone'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221222_094353_alter_table_appointment_request cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221222_094353_alter_table_appointment_request cannot be reverted.\n";

        return false;
    }
    */
}
