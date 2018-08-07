<?php

use yii\db\Migration;

/**
 * Handles the creation for table `transfers`.
 */
class m161105_105238_create_transfers_table extends Migration
{
    /**
     * Переводы
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transfers', [
            'id' => $this->primaryKey(),
            'groupFromId' => $this->integer()->notNull(),  // Группа, из которой переводится теленок
            'groupToId' => $this->integer()->notNull(),    // Группа, в которую переводится теленок
            'date' => $this->integer()->notNull(),         // Дата перевода
            'calf' => $this->integer()->notNull()          // Теленок
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('transfers');
    }
}
