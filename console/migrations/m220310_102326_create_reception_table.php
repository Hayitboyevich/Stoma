<?php

use common\helpers\MigrationHelper;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%reception}}`.
 */
class m220310_102326_create_reception_table extends Migration
{
    public $tableName = 'reception';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'             => $this->primaryKey(),
            'patient_id'     => $this->integer()->notNull()->comment('Пациент'),
            'doctor_id'      => $this->integer()->notNull()->comment('Доктор'),
            'reception_time' => $this->integer()->notNull()->comment('Время приема'),
            'comment'        => $this->text()->comment('Комментарий'),
            'status'         => $this->boolean()->notNull()->defaultValue(false)->comment('Просмотрено / Не просмотрено')
        ]);

        MigrationHelper::appendTimeColumns($this);
        MigrationHelper::createForeignKeyAndIndex($this, 'patient');
        MigrationHelper::createForeignKeyAndIndex($this, 'user', 'doctor_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        MigrationHelper::dropForeignKeyAndIndex($this, 'patient_id');
        MigrationHelper::dropForeignKeyAndIndex($this, 'doctor_id');
        $this->dropTable($this->tableName);
    }
}
