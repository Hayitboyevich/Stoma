<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%price_list}}`.
 */
class m220412_131242_create_price_list_table extends Migration
{
    public $tableName = 'price_list';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'      => $this->primaryKey(),
            'section' => $this->string()->notNull()->comment('Раздел'),
            'items'   => $this->string()->notNull()->comment('Группы и позиции')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
