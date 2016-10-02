<?php

use yii\db\Migration;

/**
 * Handles the creation for table `calf`.
 */
class m160924_115840_create_calf_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('calf', [
            'id' => $this->primaryKey(),                  // id
            'number' => $this->string(15)->notNull(),     // *Индивидуальный номер
            'nickname' => $this->string(15),              // Кличка
            'group' => $this->string(15),                 // Группа
            'birthday' => $this->dateTime()->notNull(),   // *Дата Рождения
            'gender' => $this->string(1)->notNull(),      // *Пол
            'birthWeight' => $this->float()->notNull(),   // *Вес при рождении
            'previousWeighingDate' => $this->dateTime(),  // Предыдущее взвешивание, Дата
            'previousWeighing' => $this->float(),         // Предыдущее взвешивание, Вес/кг
            'lastWeighingDate' => $this->dateTime(),      // Последнее взвешивание, Дата
            'lastWeighing' => $this->float(),             // Последнее взвешивание, Вес/кг
            'color' => $this->integer(),                  // Масть
            'motherId' => $this->integer(),               // Мать
            'fatherId' => $this->integer()                // Отец
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
