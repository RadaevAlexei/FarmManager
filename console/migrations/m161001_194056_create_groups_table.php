<?php

use yii\db\Migration;

/**
 * Handles the creation for table `groups`.
 */
class m161001_194056_create_groups_table extends Migration
{
    /**
     * Группа
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('groups', [
            'id' => $this->primaryKey(),                           // id
            'name' => $this->string(50)->notNull()->unique(),      // Название группы
            'employeeId' => $this->integer()->notNull(),           // Сотрудник(чья группа)
            'directorId' => $this->integer()->notNull(),           // Исполнительный директор
            'mainZootechnicianId' => $this->integer()->notNull(),  // Главный зоотехник
            'accountantId' => $this->integer()->notNull(),         // Бухгалтер
            'calfEmployeeId' => $this->integer()->notNull(),       // Телятник(ца)
            'directorSecurityId' => $this->integer()->notNull(),   // Начальник службы безопасности
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('groups');
    }
}
