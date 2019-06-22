<?php

use yii\db\Migration;

/**
 * Handles adding finished_at to table `appropriation_scheme`.
 */
class m190622_125048_add_finished_at_column_to_appropriation_scheme_table extends Migration
{
    private $column = 'finished_at';
    private $table = 'appropriation_scheme';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, $this->column, $this->dateTime()->after('started_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, $this->column);
    }
}
