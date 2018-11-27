<?php

use yii\db\Migration;

/**
 * Handles the creation of table `groups_action`.
 */
class m181127_185921_create_groups_action_table extends Migration
{
    private $tableName = 'groups_action';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->notNull()
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
