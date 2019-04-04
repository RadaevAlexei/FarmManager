<?php

use yii\db\Migration;

/**
 * Handles adding collar to table `animals`.
 */
class m190404_145139_add_collar_column_to_animals_table extends Migration
{
    private $tableName = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'collar', $this->integer()->after('nickname'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'collar');
    }
}
