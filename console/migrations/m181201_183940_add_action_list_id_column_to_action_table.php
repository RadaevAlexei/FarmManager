<?php

use yii\db\Migration;

/**
 * Handles adding action_list_id to table `action`.
 */
class m181201_183940_add_action_list_id_column_to_action_table extends Migration
{
    private $tableName = "action";
    private $newColumn = "action_list_id";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->newColumn, $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->newColumn);
    }
}
