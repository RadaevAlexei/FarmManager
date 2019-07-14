<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seed_bull_storage`.
 */
class m190714_133411_create_seed_bull_storage_table extends Migration
{
    private $table = 'seed_bull_storage';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'seed_bull_id' => $this->integer()->notNull(),
            'container_duara_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-seed-bull', $this->table, 'seed_bull_id', 'seed_bull', 'id');
        $this->addForeignKey('fk-container-duara', $this->table, 'container_duara_id', 'container_duara', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-seed-bull', $this->table);
        $this->dropForeignKey('fk-container-duara', $this->table);

        $this->dropTable($this->table);
    }
}
