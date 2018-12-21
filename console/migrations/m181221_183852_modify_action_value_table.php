<?php

use yii\db\Migration;

/**
 * Class m181221_183852_modify_action_value_table
 */
class m181221_183852_modify_action_value_table extends Migration
{
    private $oldTableName = 'action_value';
    private $newTableName = 'action_history';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable($this->oldTableName, $this->newTableName);

        $this->addColumn($this->newTableName, 'scheme_number', $this->integer()->notNull()->after('scheme_id'));
        $this->addColumn($this->newTableName, 'scheme_day_at', $this->dateTime()->notNull()->after('scheme_day_id'));
        $this->renameColumn($this->newTableName, 'action_list_values', 'list_value');
        $this->addColumn($this->newTableName, 'execute_at', $this->dateTime()->notNull());
        $this->addColumn($this->newTableName, 'user_id', $this->integer()->notNull());
        $this->addColumn($this->newTableName, 'status', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable($this->newTableName, $this->oldTableName);

        $this->dropColumn($this->oldTableName, 'scheme_number');
        $this->dropColumn($this->oldTableName, 'scheme_day_at');
        $this->renameColumn($this->oldTableName, 'list_value', 'action_list_values');
        $this->dropColumn($this->oldTableName, 'execute_at');
        $this->dropColumn($this->oldTableName, 'employee_id');
        $this->dropColumn($this->oldTableName, 'status');
    }
}
