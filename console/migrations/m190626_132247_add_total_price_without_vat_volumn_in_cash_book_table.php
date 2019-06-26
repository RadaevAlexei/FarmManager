<?php

use yii\db\Migration;

/**
 * Class m190626_132247_add_total_price_without_vat_volumn_in_cash_book_table
 */
class m190626_132247_add_total_price_without_vat_volumn_in_cash_book_table extends Migration
{
    private $table = 'cash_book';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'total_price_without_vat', $this->double()->after('volume'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'total_price_without_vat');
    }
}
