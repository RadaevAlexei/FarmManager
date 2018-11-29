<?php

use yii\db\Migration;

/**
 * Handles the creation of table `action_list_item`.
 */
class m181128_194031_create_action_list_item_table extends Migration
{
    private $tableName = 'action_list_item';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'             => $this->primaryKey(),
            'action_list_id' => $this->integer()->notNull(),
            'name'           => $this->string()->notNull(),
            'value'          => $this->string()->notNull(),
            'sort'           => $this->string()->notNull(),
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
