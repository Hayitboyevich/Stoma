<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%technician}}`.
 */
class m230220_061129_create_technician_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%technician_price_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'price' => $this->integer()->notNull()->comment('Прайс'),
            'technician_salary' => $this->integer()->notNull()->comment('Зарплата техника'),
            'status' => $this->smallInteger()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%technician}}');
    }
}
