<?php

use yii\db\Migration;

/**
 * Handles the creation of table `list`.
 */
class m181128_164059_create_list_table extends Migration
{
    private $tableName = 'action_list';

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
