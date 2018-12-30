<?php

use yii\db\Migration;

/**
 * Handles adding approve to table `scheme`.
 */
class m181225_193109_add_approve_column_to_scheme_table extends Migration
{
    private $tableName = 'scheme';
    private $columnName = 'approve';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->columnName, $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}
