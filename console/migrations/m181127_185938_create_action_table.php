<?php

use yii\db\Migration;

/**
 * Handles the creation of table `action`.
 */
class m181127_185938_create_action_table extends Migration
{
    private $tableName = 'action';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->integer()->notNull(),
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
