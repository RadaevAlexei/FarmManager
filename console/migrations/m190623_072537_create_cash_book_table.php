<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cash_book`.
 */
class m190623_072537_create_cash_book_table extends Migration
{
    private $tableName = 'cash_book';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'             => $this->primaryKey(),
            'user_id'        => $this->integer()->notNull(),
            'type'           => $this->integer()->notNull(),
            'date'           => $this->dateTime()->notNull(),
            'preparation_id' => $this->integer()->notNull(),
            'stock_id'       => $this->integer()->notNull(),
            'count'          => $this->integer()->notNull(),
            'volume'         => $this->double(),
            'total_price'    => $this->double(),
        ]);

        $this->addForeignKey('fk-cash-user', $this->tableName, 'user_id', 'user', 'id');
        $this->addForeignKey('fk-cash-preparation', $this->tableName, 'preparation_id', 'preparation', 'id');
        $this->addForeignKey('fk-cash-stock', $this->tableName, 'stock_id', 'stock', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cash-user', $this->tableName);
        $this->dropForeignKey('fk-cash-preparation', $this->tableName);
        $this->dropForeignKey('fk-cash-stock', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
