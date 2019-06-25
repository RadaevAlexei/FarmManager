<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stock_migration`.
 */
class m190625_194521_create_stock_migration_table extends Migration
{
    private $tableName = "stock_migration";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'             => $this->primaryKey(),
            'date'           => $this->dateTime(),
            'user_id'        => $this->integer()->notNull(),
            'preparation_id' => $this->integer()->notNull(),
            'count'          => $this->integer()->notNull(),
            'volume'         => $this->double()->notNull(),
            'stock_from_id'  => $this->integer()->notNull(),
            'stock_to_id'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-user-stock', $this->tableName, 'user_id', 'user', 'id');
        $this->addForeignKey('fk-preparation-stock-migration', $this->tableName, 'preparation_id', 'preparation',
            'id');
        $this->addForeignKey('fk-stock-from', $this->tableName, 'stock_from_id', 'stock', 'id');
        $this->addForeignKey('fk-stock-to', $this->tableName, 'stock_to_id', 'stock', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user-stock', $this->tableName);
        $this->dropForeignKey('fk-preparation-stock-migration', $this->tableName);
        $this->dropForeignKey('fk-stock-from', $this->tableName);
        $this->dropForeignKey('fk-stock-to', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
