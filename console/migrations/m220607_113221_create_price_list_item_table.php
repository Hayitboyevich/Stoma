<?php

use common\helpers\MigrationHelper;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%price_list_item}}`.
 */
class m220607_113221_create_price_list_item_table extends Migration
{
    public $tableName = 'price_list_item';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->db->getTableSchema($this->tableName, true) === null) {
            $this->createTable($this->tableName, [
                'id'            => $this->primaryKey(),
                'price_list_id' => $this->integer()->notNull()->comment('Раздел'),
                'name'          => $this->string()->notNull()->comment('Группы и позиции'),
                'parent_id'     => $this->integer(),
                'price'         => $this->integer()->notNull()->comment('Прайс')
            ]);
            MigrationHelper::createForeignKeyAndIndex($this, 'price_list');
            MigrationHelper::createForeignKeyAndIndex($this, 'price_list_item', 'parent_id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        MigrationHelper::dropForeignKeyAndIndex($this, 'price_list_id');
        MigrationHelper::dropForeignKeyAndIndex($this,  'parent_id');
        $this->dropTable($this->tableName);
    }
}
