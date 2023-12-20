<?php

use yii\db\Migration;

/**
 * Class m230222_105717_alter_table_technician_price_list
 */
class m230222_105717_alter_table_technician_price_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('technician_price_list', 'technician_salary');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230222_105717_alter_table_technician_price_list cannot be reverted.\n";

        return false;
    }
}
