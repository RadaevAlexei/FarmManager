<?php

use yii\db\Migration;

/**
 * Handles the creation of table `animal_group`.
 */
class m190624_083605_create_animal_group_table extends Migration
{
    private $tableName = 'animal_group';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
