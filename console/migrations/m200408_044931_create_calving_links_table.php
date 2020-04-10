<?php

use yii\db\Migration;

/**
 * Handles the creation of table `calving_links`.
 */
class m200408_044931_create_calving_links_table extends Migration
{
    private $tableName = 'calving_links';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'calving_id' => $this->integer()->notNull(),
            'child_animal_id' => $this->integer()->notNull()
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
