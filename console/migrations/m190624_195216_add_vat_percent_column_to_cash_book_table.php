<?php

use yii\db\Migration;

/**
 * Handles adding vat_percent to table `cash_book`.
 */
class m190624_195216_add_vat_percent_column_to_cash_book_table extends Migration
{
    private $table = 'cash_book';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'vat_percent', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'vat_percent');
    }
}
