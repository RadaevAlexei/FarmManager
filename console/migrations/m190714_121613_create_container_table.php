<?php

use yii\db\Migration;

/**
 * Handles the creation of table `container`.
 */
class m190714_121613_create_container_table extends Migration
{
    private $tableName = 'container_duara';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'   => $this->primaryKey(),
            'name' => $this->string(250)->notNull()
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
