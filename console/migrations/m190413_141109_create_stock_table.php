<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stock`.
 */
class m190413_141109_create_stock_table extends Migration
{
    private $tableName = 'stock';

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
