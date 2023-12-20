<?php

use yii\db\Migration;

/**
 * Class m221209_093658_alter_patient_table
 */
class m221209_093658_alter_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('patient', 'note_update_at', $this->timestamp()->after('gender')->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('patient', 'note', $this->text()->after('gender'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('patient', 'note_update_at');
        $this->dropColumn('patient', 'note');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221209_093658_alter_patient_table cannot be reverted.\n";

        return false;
    }
    */
}
