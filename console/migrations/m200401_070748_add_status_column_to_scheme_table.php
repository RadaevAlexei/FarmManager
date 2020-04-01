<?php

use yii\db\Migration;

/**
 * Handles adding status to table `scheme`.
 */
class m200401_070748_add_status_column_to_scheme_table extends Migration
{
    private $table = 'scheme';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'status', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'status');
    }
}
