<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%old_patient}}`.
 */
class m221016_204215_create_old_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%old_patient}}', [
            'id'                        => $this->primaryKey()->comment('ID ПАЦИЕНТА'),
            'card_number'               => $this->integer()->comment('НОМЕР КАРТЫ'),
            'first_visit'               => $this->timestamp()->comment('ПЕРВЫЙ ВИЗИТ'),
            'last_name'                 => $this->string()->comment('ФАМИЛИЯ'),
            'first_name'                => $this->string()->comment('ИМЯ'),
            'patronymic'                => $this->string()->null()->comment('ОТЧЕСТВО'),
            'gender'                    => $this->string(255)->comment('ПОЛ'),
            'dob'                       => $this->date()->null()->comment('ДАТА РОЖДЕНИЯ'),
            'phone'                     => $this->string()->comment('МОБ. ТЕЛЕФОН'),
            'phone_home'                => $this->string()->comment('ДОМ. ТЕЛЕФОН'),
            'phone_work'                => $this->string()->comment('РАБ. ТЕЛЕФОН'),
            'discount'                  => $this->integer()->comment('СКИДКА, %'),
            'home_address'              => $this->string()->comment('ДОМ. АДРЕС'),
            'doctor_id'                 => $this->integer()->comment('ID ЛЕЧАЩЕГО ВРАЧА'),
            'hygienist_id'              => $this->integer()->comment('ID ГИГИЕНИСТА'),
            'source'                    => $this->string()->comment('ИСТОЧНИК'),
            'recommended_patient'       => $this->string()->comment('КТО РЕКОМЕНДОВАЛ (НОМЕР КАРТЫ ПАЦИЕНТА)'),
            'recommended_user'          => $this->string()->comment('КТО РЕКОМЕНДОВАЛ (СОТРУДНИК)'),
            'who_were_recommended'      => $this->string()->comment('КОГО РЕКОМЕНДОВАЛИ'),
            'patient_status'            => $this->string()->comment('СТАТУС ПАЦИЕНТА'),
            'debt'                      => $this->integer()->comment('ДОЛГ'),
            'credit'                    => $this->integer()->comment('АВАНС'),
            'recommendations_amount'    => $this->integer()->comment('КОЛ-ВО РЕКОМЕНДАЦИЙ'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%old_patient}}');
    }
}
