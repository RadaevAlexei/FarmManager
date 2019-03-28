<?php

use yii\db\Migration;

/**
 * Handles adding scheme_day to table `action_history`.
 */
class m190328_195608_add_scheme_day_column_to_action_history_table extends Migration
{
    private $tableName = 'action_history';
    private $columnName = 'scheme_day';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->columnName, $this->integer()->notNull()->after('scheme_day_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}
