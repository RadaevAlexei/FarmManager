<?php

use yii\db\Migration;

/**
 * Handles adding price to table `seed_bull`.
 */
class m190714_134343_add_price_column_to_seed_bull_table extends Migration
{
    private $table = 'seed_bull';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'price', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'price');
    }
}
