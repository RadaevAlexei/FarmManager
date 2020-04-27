<?php

use yii\db\Migration;

/**
 * Handles the creation of table `insemination_rectal_link`.
 */
class m200419_164306_create_insemination_rectal_link_table extends Migration
{
    private $tableName = 'insemination_rectal_link';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'              => $this->primaryKey(),
            'prev_id'         => $this->integer(),
            'animal_id'       => $this->integer()->notNull(),
            'insemination_id' => $this->integer()->notNull(),
            'rectal_id'       => $this->integer()->notNull(),
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
