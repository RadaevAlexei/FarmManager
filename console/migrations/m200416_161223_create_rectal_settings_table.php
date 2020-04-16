<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rectal_settings`.
 */
class m200416_161223_create_rectal_settings_table extends Migration
{
    private $tableName = 'rectal_settings';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'pregnancy_time' => $this->integer()->notNull(),
            'end_time' => $this->integer()->notNull(),
            'first_day' => $this->integer()->notNull(),
            'confirm_first' => $this->integer()->notNull(),
            'confirm_second' => $this->integer()->notNull(),
        ]);

        $this->insert($this->tableName, [
            'pregnancy_time' => 278,
            'end_time' => 218,
            'first_day' => 28,
            'confirm_first' => 56,
            'confirm_second' => 205,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
