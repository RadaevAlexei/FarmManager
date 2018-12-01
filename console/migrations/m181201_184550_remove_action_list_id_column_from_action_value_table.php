<?php

use yii\db\Migration;

/**
 * Class m181201_184550_remove_action_list_id_column_from_action_value_table
 */
class m181201_184550_remove_action_list_id_column_from_action_value_table extends Migration
{
    private $tableName = "action_value";
    private $column = "action_list_id";
    private $fkActionListName = "fk-action_list";
    private $refTableList = 'action_list';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey($this->fkActionListName, $this->tableName);
        $this->dropColumn($this->tableName, $this->column);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn($this->tableName, $this->column, $this->integer());
        $this->addForeignKey($this->fkActionListName, $this->tableName, $this->column, $this->refTableList, 'id');
    }
}
