<?php

use yii\db\Migration;

/**
 * Class m190412_075715_remove_excess_columns_from_preparation_table
 */
class m190412_075715_remove_excess_columns_from_preparation_table extends Migration
{
    private $tableName = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'receipt_date');
        $this->dropColumn($this->tableName, 'packing');
        $this->dropColumn($this->tableName, 'volume');
        $this->dropColumn($this->tableName, 'price');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
