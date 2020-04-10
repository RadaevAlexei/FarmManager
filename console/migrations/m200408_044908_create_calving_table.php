<?php

use yii\db\Migration;

/**
 * Handles the creation of table `calving`.
 */
class m200408_044908_create_calving_table extends Migration
{
    private $tableName = 'calving';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'animal_id' => $this->integer()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'status' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull(),
            'note' => $this->text(),
            'user_id' => $this->integer()->notNull(),
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
