<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%old_employee}}`.
 */
class m221017_210352_create_old_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%old_employee}}', [
            'id'              => $this->primaryKey()->comment('ID СОТРУДНИКА'),
            'last_name'       => $this->string()->comment('ФАМИЛИЯ'),
            'first_name'      => $this->string()->comment('ИМЯ'),
            'patronymic'      => $this->string()->comment('ОТЧЕСТВО'),
            'dob'             => $this->date()->comment('ДАТА РОЖДЕНИЯ'),
            'status'          => $this->string()->comment('СТАТУС'),
            'work_start_date' => $this->date()->comment('ДАТА НАЧАЛА РАБОТЫ'),
            'work_end_date'   => $this->date()->comment('ДАТА ОКОНЧАНИЯ РАБОТЫ'),
            'email'           => $this->string()->comment('EMAIL'),
            'initials'        => $this->string()->comment('ФИО СОКРАЩЕННО'),
            'phone'           => $this->string()->comment('МОБ. ТЕЛЕФОН'),
            'phone_home'      => $this->string()->comment('ДОМ. ТЕЛЕФОН'),
            'phone_work'      => $this->string()->comment('РАБ. ТЕЛЕФОН'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%old_employee}}');
    }
}
