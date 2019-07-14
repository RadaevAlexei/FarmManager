<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seed_cash_book`.
 */
class m190714_131156_create_seed_cash_book_table extends Migration
{
    private $tableName = 'seed_cash_book';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'type' => $this->integer()->notNull(),
            'seed_bull_id' => $this->integer()->notNull(),
            'container_duara_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
            'total_price_without_vat' => $this->double(),
            'total_price_with_vat' => $this->double(),
            'vat_percent' => $this->double(),
        ]);

        $this->addForeignKey('fk-seed-cash-user', $this->tableName, 'user_id', 'user', 'id');
        $this->addForeignKey('fk-seed-cash-seed-bull', $this->tableName, 'seed_bull_id', 'seed_bull', 'id');
        $this->addForeignKey('fk-seed-cash-container-duara', $this->tableName, 'container_duara_id', 'container_duara',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-seed-cash-user', $this->tableName);
        $this->dropForeignKey('fk-seed-cash-seed-bull', $this->tableName);
        $this->dropForeignKey('fk-seed-cash-container-duara', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
