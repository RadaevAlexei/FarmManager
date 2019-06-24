<?php

use yii\db\Migration;

/**
 * Handles adding animal_group_id to table `animals`.
 */
class m190624_103823_add_animal_group_id_column_to_animals_table extends Migration
{
    private $column = 'animal_group_id';
    private $table = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, $this->column, $this->integer()->after('cowshed_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, $this->column);
    }
}
