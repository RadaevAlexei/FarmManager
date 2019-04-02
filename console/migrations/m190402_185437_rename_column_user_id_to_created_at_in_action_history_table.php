<?php

use yii\db\Migration;

/**
 * Class m190402_185437_rename_column_user_id_to_created_at_in_action_history_table
 */
class m190402_185437_rename_column_user_id_to_created_at_in_action_history_table extends Migration
{
    private $tableName = 'action_history';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn($this->tableName, 'user_id', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn($this->tableName, 'created_at', '}');
    }
}
