<?php

use yii\db\Migration;

/**
 * Class m230117_052650_alter_table_price_list_item
 */
class m230117_052650_alter_table_price_list_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('price_list_item', 'profitability', $this->integer()->after('parent_id')->comment('Рентабельность')->defaultValue(0));
        $this->addColumn('price_list_item', 'remains', $this->integer()->after('parent_id')->comment('Остаток')->defaultValue(0));
        $this->addColumn('price_list_item', 'other_expenses', $this->float()->after('parent_id')->comment('Прочие расходы')->defaultValue(0));
        $this->addColumn('price_list_item', 'marketing', $this->float()->after('parent_id')->comment('Маркетинг')->defaultValue(0));
        $this->addColumn('price_list_item', 'amortization', $this->float()->after('parent_id')->comment('Аммортизация оборудования и обслуживание')->defaultValue(0));
        $this->addColumn('price_list_item', 'utilities', $this->float()->after('parent_id')->comment('Комунальные услуги')->defaultValue(0));
        $this->addColumn('price_list_item', 'consumable', $this->float()->after('parent_id')->comment('Расходный материал')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230117_052650_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230117_052650_alter_table_price_list_item cannot be reverted.\n";

        return false;
    }
    */
}
