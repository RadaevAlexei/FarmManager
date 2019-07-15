<?php

use yii\db\Migration;

/**
 * Handles the creation of table `insemination`.
 */
class m190715_185244_create_insemination_table extends Migration
{
    private $tableName = 'insemination';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                 => $this->primaryKey(),
            'user_id'            => $this->integer()->notNull(),
            'date'               => $this->dateTime()->notNull(),
            'animal_id'          => $this->integer()->notNull(),
            'seed_bull_id'       => $this->integer()->notNull(),
            'count'              => $this->integer()->notNull(),
            'container_duara_id' => $this->integer()->notNull(),
            'type_insemination'  => $this->integer()->notNull(),
            'comment'            => $this->string(),
        ]);

        $this->addForeignKey('fk-insemination-user', $this->tableName, 'user_id', 'user', 'id');
        $this->addForeignKey('fk-insemination-animal', $this->tableName, 'animal_id', 'animals', 'id');
        $this->addForeignKey('fk-insemination-seed-bull', $this->tableName, 'seed_bull_id', 'seed_bull', 'id');
        $this->addForeignKey('fk-insemination-container-duara', $this->tableName, 'container_duara_id', 'container_duara', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-insemination-user', $this->tableName);
        $this->dropForeignKey('fk-insemination-animal', $this->tableName);
        $this->dropForeignKey('fk-insemination-seed-bull', $this->tableName);
        $this->dropForeignKey('fk-insemination-container-duara', $this->tableName);

        $this->dropTable('insemination');
    }
}
