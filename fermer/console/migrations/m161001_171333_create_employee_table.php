<?php

use yii\db\Migration;

/**
 * Handles the creation for table `employee`.
 */
class m161001_171333_create_employee_table extends Migration
{
    /**
     * Сотрудник
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('employee', [
            'id' => $this->primaryKey(),                   // id
            'firstName' => $this->string(20)->notNull(),   // Имя
            'lastName' => $this->string(20)->notNull(),    // Фамилия
            'middleName' => $this->string(20)->notNull(),  // Отчество
            'birthday' => $this->integer(),                // День Рождения
            'gender' => $this->smallInteger()->notNull(),  // Пол
            'functionId' => $this->integer()->notNull(),   // Должность
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('employee');
    }
}
