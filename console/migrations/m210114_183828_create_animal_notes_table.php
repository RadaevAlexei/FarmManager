<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%animal_notes}}`.
 */
class m210114_183828_create_animal_notes_table extends Migration
{
    private $tableName = '{{%animal_note}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'animal_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'description' => $this->text()->notNull(),
        ]);

        $this->addForeignKey('fk-animal_note-animal', $this->tableName, 'animal_id', 'animals', 'id');
        $this->addForeignKey('fk-animal_note-user', $this->tableName, 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-animal_note-animal', $this->tableName);
        $this->dropForeignKey('fk-animal_note-user', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
