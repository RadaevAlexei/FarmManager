<?php

use yii\db\Migration;

/**
 * Handles the creation of table `animal_history`.
 */
class m190402_194951_create_animal_history_table extends Migration
{
    private $tableName = 'animal_history';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'animal_id'   => $this->integer()->notNull(),
            'user_id'     => $this->integer()->notNull(),
            'date'        => $this->dateTime()->notNull(),
            'action_type' => $this->string(255)->notNull(),
            'action_text' => $this->string(255)->notNull(),
        ]);

        $this->addForeignKey('fk-animal', $this->tableName, 'animal_id', 'animals', 'id');
        $this->addForeignKey('fk-user', $this->tableName, 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-animal', $this->tableName);
        $this->dropForeignKey('fk-user', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
