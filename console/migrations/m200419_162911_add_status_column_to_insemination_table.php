<?php

use yii\db\Migration;

/**
 * Handles adding status to table `insemination`.
 */
class m200419_162911_add_status_column_to_insemination_table extends Migration
{
    private $tableName = 'insemination';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }
}
