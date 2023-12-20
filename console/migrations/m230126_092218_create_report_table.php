<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m230126_092218_create_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer(),
            'patient_id' => $this->integer(),
            'visit_time' => $this->timestamp(),
            'doctor_id' => $this->integer(),
            'doctor_cat_percent' => $this->float(),
            'doctor_earnings' => $this->integer(),
            'assistant_id' => $this->integer(),
            'assistant_cat_percent' => $this->float(),
            'assistant_earnings' => $this->integer(),
            'price_list_id' => $this->integer(),
            'price_list_item_id' => $this->integer(),
            'sub_cat_price' => $this->integer(),
            'sub_cat_amount' => $this->integer(),
            'teeth_amount' => $this->integer(),
            'consumable' => $this->float()->comment('Расходный материал'),
            'utilities' => $this->float()->comment('Комунальные услуги'),
            'amortization' => $this->float()->comment('Аммортизация оборудования и обслуживание'),
            'marketing' => $this->float()->comment('Маркетинг'),
            'other_expenses' => $this->float()->comment('Прочие расходы'),
            'remains' => $this->integer()->comment('Остаток'),
            'profitability' => $this->float()->comment('Рентабельность'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-report-invoice_id',
            'report',
            'invoice_id'
        );

        $this->createIndex(
            'idx-report-patient_id',
            'report',
            'patient_id'
        );

        $this->createIndex(
            'idx-report-doctor_id',
            'report',
            'doctor_id'
        );

        $this->createIndex(
            'idx-report-assistant_id',
            'report',
            'assistant_id'
        );

        $this->createIndex(
            'idx-report-price_list_id',
            'report',
            'price_list_id'
        );

        $this->createIndex(
            'idx-report-price_list_item_id',
            'report',
            'price_list_item_id'
        );

        $this->addForeignKey(
            'fk-report-invoice_id',
            'report',
            'invoice_id',
            'invoice',
            'id'
        );

        $this->addForeignKey(
            'fk-report-patient_id',
            'report',
            'patient_id',
            'patient',
            'id'
        );

        $this->addForeignKey(
            'fk-report-doctor_id',
            'report',
            'doctor_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-report-assistant_id',
            'report',
            'assistant_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-report-price_list_id',
            'report',
            'price_list_id',
            'price_list',
            'id'
        );

        $this->addForeignKey(
            'fk-report-price_list_item_id',
            'report',
            'price_list_item_id',
            'price_list_item',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-report-invoice_id', 'report');
        $this->dropForeignKey('fk-report-patient_id', 'report');
        $this->dropForeignKey('fk-report-doctor_id', 'report');
        $this->dropForeignKey('fk-report-assistant_id', 'report');
        $this->dropForeignKey('fk-report-price_list_id', 'report');
        $this->dropForeignKey('fk-report-price_list_item_id', 'report');

        $this->dropTable('{{%report}}');
    }
}
