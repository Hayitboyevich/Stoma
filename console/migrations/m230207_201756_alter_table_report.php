<?php

use yii\db\Migration;

/**
 * Class m230207_201756_alter_table_report
 */
class m230207_201756_alter_table_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'report',
            'price_with_discount',
            $this->integer()
                ->defaultValue(0)
                ->after('sub_cat_price')
                ->comment('Цена подкатегории со скидкой')
        );

        $this->addColumn(
            'report',
            'discount',
            $this->integer()
                ->defaultValue(0)
                ->after('patient_id')
                ->comment('Скидка пациент')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('report', 'price_with_discount');
        $this->dropColumn('report', 'discount');
    }
}
