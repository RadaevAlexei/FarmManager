<?php

use yii\db\Migration;

/**
 * Class m181212_202158_remove_value_column_from_action_list_item_table
 */
class m181212_202158_remove_value_column_from_action_list_item_table extends Migration
{
    private $tableName = "action_list_item";
    private $column = "value";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, $this->column);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn($this->tableName, $this->column, $this->string()->notNull());
    }
}
