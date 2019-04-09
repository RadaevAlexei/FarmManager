<?php

use yii\db\Migration;

/**
 * Class m190409_081332_remove_scheme_number_column_from_action_history_table
 */
class m190409_081332_remove_scheme_number_column_from_action_history_table extends Migration
{
    private $tableName = 'action_history';

    private $column = 'scheme_number';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, $this->column);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
