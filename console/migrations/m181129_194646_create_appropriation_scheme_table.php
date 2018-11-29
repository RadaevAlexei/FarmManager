<?php

use yii\db\Migration;

/**
 * Handles the creation of table `appropriation_scheme`.
 */
class m181129_194646_create_appropriation_scheme_table extends Migration
{
    private $tableName = 'appropriation_scheme';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'animal_id'  => $this->integer()->notNull(),
            'scheme_id'  => $this->integer()->notNull(),
            'status'     => $this->integer()->notNull()->defaultValue(0),
            'started_at' => $this->dateTime()->notNull()
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
