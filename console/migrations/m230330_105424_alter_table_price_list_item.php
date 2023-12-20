<?php

use yii\db\Migration;

/**
 * Class m230330_105424_alter_table_price_list_item
 */
class m230330_105424_alter_table_price_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'price_list_item',
            'discount_apply',
            $this->boolean()->defaultValue(true)->comment('Применять скидку')->after('parent_id')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230330_105424_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }
}
