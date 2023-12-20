<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doctor_category}}`.
 */
class m220526_063709_create_doctor_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_category}}', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        $this->addForeignKey('fk-doctor_category-doctor_id','doctor_category','doctor_id','user','id');
        $this->addForeignKey('fk-doctor_category-category_id','doctor_category','category_id','price_list','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-doctor_category-doctor_id','doctor_category');
        $this->dropForeignKey('fk-doctor_category-category_id','doctor_category');
        $this->dropTable('{{%doctor_category}}');
    }
}
