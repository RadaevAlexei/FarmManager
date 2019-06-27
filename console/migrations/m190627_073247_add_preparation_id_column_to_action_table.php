<?php

use yii\db\Migration;

/**
 * Handles adding preparation_id to table `action`.
 */
class m190627_073247_add_preparation_id_column_to_action_table extends Migration
{
    private $table = 'action';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'preparation_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'preparation_id');
    }
}
