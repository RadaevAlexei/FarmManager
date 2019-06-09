<?php

use yii\db\Migration;

/**
 * Class m190609_053055_change_type_columns_period_in_preparation_table
 */
class m190609_053055_change_type_columns_period_in_preparation_table extends Migration
{
    private $table = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->table, 'period_milk_day', $this->double(1));
        $this->alterColumn($this->table, 'period_milk_hour', $this->double(1));
        $this->alterColumn($this->table, 'period_meat_day', $this->double(1));
        $this->alterColumn($this->table, 'period_meat_hour', $this->double(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
