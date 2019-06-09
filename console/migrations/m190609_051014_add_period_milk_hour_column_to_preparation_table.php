<?php

use yii\db\Migration;

/**
 * Handles adding period_milk_hour to table `preparation`.
 */
class m190609_051014_add_period_milk_hour_column_to_preparation_table extends Migration
{
    private $table = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'period_milk_hour', $this->integer()->after('period_milk_day'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'period_milk_hour');
    }
}
