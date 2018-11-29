<?php

use yii\db\Migration;

/**
 * Handles the creation of table `groups_action_link`.
 */
class m181129_194111_create_groups_action_link_table extends Migration
{
    private $tableName = 'groups_action_link';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'               => $this->primaryKey(),
            'groups_action_id' => $this->integer()->notNull(),
            'action_id'        => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
