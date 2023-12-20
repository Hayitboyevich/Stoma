<?php

use yii\db\Migration;

/**
 * Class m230203_063248_alter_table_invoice_services
 */
class m230203_063248_alter_table_invoice_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('invoice_services', 'price_with_discount', $this->integer()
            ->defaultValue(0)
            ->after('price')
            ->comment('Цена со скидкой')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('invoice_services', 'price_with_discount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230203_063248_alter_table_invoice_services cannot be reverted.\n";

        return false;
    }
    */
}
