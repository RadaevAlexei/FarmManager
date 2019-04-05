<?php

use yii\db\Migration;

/**
 * Handles adding diagnosis to table `animals`.
 */
class m190405_091609_add_diagnosis_column_to_animals_table extends Migration
{
    private $tableName = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'diagnosis', $this->integer()->after('health_status'));
        $this->addForeignKey('fk-animal-diagnosis', $this->tableName, 'diagnosis', 'diagnosis', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-animal-diagnosis', $this->tableName);
        $this->dropColumn($this->tableName, 'diagnosis');
    }
}
