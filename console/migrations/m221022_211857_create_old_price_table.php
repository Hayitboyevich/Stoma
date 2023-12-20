<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%old_price}}`.
 */
class m221022_211857_create_old_price_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%old_price}}', [
            'id' => $this->primaryKey(),
            'section_code' => $this->integer()->comment('КОД РАЗДЕЛА'),
            'section' => $this->string()->comment('РАЗДЕЛ'),
            'group' => $this->string()->comment('ГРУППА'),
            'position_code' => $this->integer()->comment('КОД ПОЗИЦИИ'),
            'position' => $this->string()->comment('ПОЗИЦИЯ'),
            'price' => $this->integer()->comment('ЦЕНА'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%old_price}}');
    }
}
