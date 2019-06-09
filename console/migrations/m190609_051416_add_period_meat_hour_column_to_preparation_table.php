<?php

use yii\db\Migration;

/**
 * Handles adding period_meat_hour to table `preparation`.
 */
class m190609_051416_add_period_meat_hour_column_to_preparation_table extends Migration
{
    private $table = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'period_meat_hour', $this->integer()->after('period_meat_day'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'period_meat_hour');
    }
}
