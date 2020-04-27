<?php

use yii\db\Migration;

/**
 * Handles adding fremartin to table `animals`.
 */
class m200427_084506_add_fremartin_column_to_animals_table extends Migration
{
    private $tableName = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            $this->tableName,
            'fremartin',
            $this->smallInteger()->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'fremartin');
    }
}
