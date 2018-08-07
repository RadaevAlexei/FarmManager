<?php

use yii\db\Migration;

/**
 * Handles the creation for table `suspension`.
 */
class m161016_090019_create_suspension_table extends Migration
{
    /**
     * Перевески(взвешивания)
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('suspension', [
            'id' => $this->primaryKey(),             // Идентификатор
            'date' => $this->integer()->notNull(),   // Дата взвешивания
            'calf' => $this->integer()->notNull(),   // Теленок
            'weight' => $this->float()->notNull()    // Вес
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('suspension');
    }
}
