<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m220228_103609_add_firstname_lastname_phone_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','firstname',$this->string());
        $this->addColumn('user','lastname',$this->string());
        $this->addColumn('user','phone',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','firstname');
        $this->dropColumn('user','lastname');
        $this->dropColumn('user','phone');
    }
}
