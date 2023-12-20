<?php

use yii\db\Migration;

/**
 * Class m230210_104424_alter_tables
 */
class m230210_104424_alter_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('report', 'remains', $this->float()->comment('Остаток'));
        $this->alterColumn('report', 'amortization', $this->float()->comment('Амортизация оборудования и обслуживание'));
        $this->alterColumn('report', 'utilities', $this->float()->comment('Коммунальные услуги'));

        $this->alterColumn('price_list_item', 'amortization', $this->float()->comment('Амортизация оборудования и обслуживание'));
        $this->alterColumn('price_list_item', 'utilities', $this->float()->comment('Коммунальные услуги'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230210_104424_alter_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230210_104424_alter_tables cannot be reverted.\n";

        return false;
    }
    */
}
