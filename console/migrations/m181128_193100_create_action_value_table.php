<?php

use yii\db\Migration;

/**
 * Handles the creation of table `action_value`.
 */
class m181128_193100_create_action_value_table extends Migration
{
    private $tableName = 'action_value';

    private $refTableScheme = 'scheme';
    private $refTableDay = 'scheme_day';
    private $refTableGroupsAction = 'groups_action';
    private $refTableAction = 'action';
    private $refTableList = 'action_list';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                 => $this->primaryKey(),
            'scheme_id'          => $this->integer()->notNull(),
            'scheme_day_id'      => $this->integer()->notNull(),
            'groups_action_id'   => $this->integer()->notNull(),
            'action_id'          => $this->integer()->notNull(),
            'text_value'         => $this->string(),
            'number_value'       => $this->integer(),
            'double_value'       => $this->double(),
            'action_list_id'     => $this->integer(),
            'action_list_values' => $this->string(),
        ]);

        $this->addForeignKey('fk-scheme', $this->tableName, 'scheme_id', $this->refTableScheme, 'id');
        $this->addForeignKey('fk-scheme_day', $this->tableName, 'scheme_day_id', $this->refTableDay, 'id');
        $this->addForeignKey('fk-groups_action', $this->tableName, 'groups_action_id', $this->refTableGroupsAction,
            'id');
        $this->addForeignKey('fk-action', $this->tableName, 'action_id', $this->refTableAction, 'id');
        $this->addForeignKey('fk-action_list', $this->tableName, 'action_list_id', $this->refTableList, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-scheme', $this->tableName);
        $this->dropForeignKey('fk-scheme_day', $this->tableName);
        $this->dropForeignKey('fk-groups_action', $this->tableName);
        $this->dropForeignKey('fk-action', $this->tableName);
        $this->dropForeignKey('fk-action_list', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
