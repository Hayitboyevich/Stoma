<?php

use yii\db\Migration;

/**
 * Class m230203_100608_alter_table_employee_salary
 */
class m230203_100608_alter_table_employee_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'employee_salary',
            'price_with_discount',
            $this->integer()
            ->defaultValue(0)
            ->after('sub_cat_price')
            ->comment('Цена подкатегории со скидкой')
        );

        $this->addColumn(
            'employee_salary',
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
        $this->dropColumn('employee_salary', 'price_with_discount');
        $this->dropColumn('employee_salary', 'discount');
    }
}
