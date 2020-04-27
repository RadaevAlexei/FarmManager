<?php

use yii\db\Migration;

/**
 * Handles adding rectal_stage to table `rectal`.
 */
class m200421_072903_add_rectal_stage_column_to_rectal_table extends Migration
{
    private $tableName = 'rectal';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'rectal_stage', $this->integer()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'rectal_stage');
    }
}
