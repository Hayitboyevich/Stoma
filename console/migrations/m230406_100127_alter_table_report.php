<?php

use yii\db\Migration;

/**
 * Class m230406_100127_alter_table_report
 */
class m230406_100127_alter_table_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('report', 'technician_id', $this->integer()->after('assistant_earnings'));
        $this->addColumn(
            'report',
            'tech_cat_id',
            $this->integer()->after('technician_id')->comment('ID категории техника')
        );
        $this->addColumn(
            'report',
            'tech_cat_price',
            $this->integer()->after('tech_cat_id')->comment('Цена категории техника')->defaultValue(0)
        );
        $this->addColumn(
            'report',
            'technician_earnings',
            $this->integer()->after('tech_cat_price')->comment('Зарплата техника')->defaultValue(0)
        );

        $this->createIndex(
            'idx-report-technician_id',
            'report',
            'technician_id'
        );

        $this->addForeignKey(
            'fk-report-technician_id',
            'report',
            'technician_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-report-tech_cat_id',
            'report',
            'tech_cat_id',
            'technician_price_list',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230406_100127_alter_table_report cannot be reverted.\n";

        return false;
    }
}
