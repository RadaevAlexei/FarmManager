<?php

use yii\db\Migration;

/**
 * Handles adding date_health to table `animals`.
 */
class m190408_132119_add_date_health_column_to_animals_table extends Migration
{
    private $tableName = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'date_health', $this->dateTime()->after('health_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'date_health');
    }
}
