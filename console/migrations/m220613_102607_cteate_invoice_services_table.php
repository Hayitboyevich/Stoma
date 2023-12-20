<?php

use common\helpers\MigrationHelper;
use yii\db\Migration;

/**
 * Class m220613_102607_cteate_invoice_services_table
 */
class m220613_102607_cteate_invoice_services_table extends Migration
{
    private $tableName = 'invoice_services';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                    => $this->primaryKey(),
            'invoice_id'            => $this->integer()->notNull()->comment("Счет"),
            'price_list_item_id'    => $this->integer()->notNull()->comment("Виды работ"),
            'amount'                => $this->smallInteger()->defaultValue(1)->comment("Количество"),
            'price'                 => $this->integer()->notNull()->comment("Цена"),
            'teeth'                 => $this->string(255)->notNull()->comment("Зубы"),
        ]);

        $this->addForeignKey('fk-invoice_services-invoice_id','invoice_services','invoice_id','invoice','id');
        $this->addForeignKey('fk-invoice_services-price_list_item_id','invoice_services','price_list_item_id','price_list_item','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        MigrationHelper::dropForeignKeyAndIndex($this, 'invoice_id');
        MigrationHelper::dropForeignKeyAndIndex($this, 'price_list_item_id');
        $this->dropTable($this->tableName);
        return false;
    }
}
