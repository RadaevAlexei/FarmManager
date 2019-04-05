<?php

use yii\db\Migration;

/**
 * Handles adding health_status to table `animals`.
 */
class m190405_091146_add_health_status_column_to_animals_table extends Migration
{
    private $tableName = 'animals';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn($this->tableName, 'health_status', $this->integer()->after('status')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn($this->tableName, 'health_status');
    }
}
