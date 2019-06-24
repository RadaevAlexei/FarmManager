<?php

use yii\db\Migration;

/**
 * Handles the creation of table `storage`.
 */
class m190622_204820_create_storage_table extends Migration
{
    private $table = 'storage';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table, [
            'id'             => $this->primaryKey(),
            'preparation_id' => $this->integer()->notNull(),
            'stock_id'       => $this->integer()->notNull(),
            'count'          => $this->integer()->notNull(),
            'volume'         => $this->double()->notNull(),
        ]);

        $this->addForeignKey('fk-preparation', $this->table, 'preparation_id', 'preparation', 'id');
        $this->addForeignKey('fk-stock', $this->table, 'stock_id', 'stock', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-preparation', $this->table);
        $this->dropForeignKey('fk-stock', $this->table);

        $this->dropTable($this->table);
    }
}
