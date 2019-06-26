<?php

use yii\db\Migration;

/**
 * Class m190626_131922_rename_column_total_price_to_total_price_with_vat_in_cash_book_table
 */
class m190626_131922_rename_column_total_price_to_total_price_with_vat_in_cash_book_table extends Migration
{
    private $tableName = 'cash_book';

    private $oldColumnName = 'total_price';
    private $newColumnName = 'total_price_with_vat';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn($this->tableName, $this->oldColumnName, $this->newColumnName);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn($this->tableName, $this->newColumnName, $this->oldColumnName);
    }

}
