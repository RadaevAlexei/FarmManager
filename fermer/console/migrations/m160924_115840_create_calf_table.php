<?php

use yii\db\Migration;

/**
 * Handles the creation for table `calf`.
 */
class m160924_115840_create_calf_table extends Migration
{
    /**
     * Телёнок
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('calf', [
            'id' => $this->primaryKey(),                  // id
            'number' => $this->string(15)->notNull(),     // *Индивидуальный номер
            'nickname' => $this->string(15),              // Кличка
            'groupId' => $this->integer() ,               // Группа
            'birthday' => $this->integer()->notNull(),    // *Дата Рождения
            'gender' => $this->string(1)->notNull(),      // *Пол
            'birthWeight' => $this->float()->notNull(),   // *Вес при рождении
            'previousWeighingDate' => $this->integer(),   // Предыдущее взвешивание, Дата
            'previousWeighing' => $this->float(),         // Предыдущее взвешивание, Вес/кг
            'currentWeighingDate' => $this->integer(),    // Текущее взвешивание, Дата
            'currentWeighing' => $this->float(),          // Текущее взвешивание, Вес/кг
            'color' => $this->integer(),                  // Масть
            'motherId' => $this->integer(),               // Мать
            'fatherId' => $this->integer(),               // Отец
            'created_at' => $this->integer(),             // Дата создания
            'updated_at' => $this->integer(),             // Дата обновления
            'deleted_at' => $this->integer(),             // Дата удаления(пометка на удаление)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('calf');
    }
}
