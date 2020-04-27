<?php

use yii\db\Migration;

/**
 * Handles adding cur_insemination_id to table `animals`.
 */
class m200420_154358_add_cur_insemination_id_column_to_animals_table extends Migration
{
    private $tableName = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'cur_insemination_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'cur_insemination_id');
    }
}
