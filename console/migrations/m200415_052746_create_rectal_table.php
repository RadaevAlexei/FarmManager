<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rectal`.
 */
class m200415_052746_create_rectal_table extends Migration
{
    private $tableName = 'rectal';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'animal_id' => $this->integer()->notNull(),
            'result' => $this->integer()->notNull(),
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
