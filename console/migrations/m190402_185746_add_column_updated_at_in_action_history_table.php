<?php

use yii\db\Migration;

/**
 * Class m190402_185746_add_column_updated_at_in_action_history_table
 */
class m190402_185746_add_column_updated_at_in_action_history_table extends Migration
{
    private $tableName = 'action_history';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'updated_at', $this->integer()->notNull()->after('created_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'updated_at');
    }
}
