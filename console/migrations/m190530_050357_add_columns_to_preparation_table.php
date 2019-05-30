<?php

use yii\db\Migration;

/**
 * Class m190530_050357_add_columns_to_preparation_table
 */
class m190530_050357_add_columns_to_preparation_table extends Migration
{
    private $table = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'category', $this->integer()->notNull());
        $this->addColumn($this->table, 'danger_class', $this->integer());
        $this->addColumn($this->table, 'period_milk', $this->integer());
        $this->addColumn($this->table, 'period_meat', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'category');
        $this->dropColumn($this->table, 'danger_class');
        $this->dropColumn($this->table, 'period_milk');
        $this->dropColumn($this->table, 'period_meat');
    }
}
