<?php

use yii\db\Migration;

/**
 * Handles the creation of table `scheme_day`.
 */
class m181128_192539_create_scheme_day_table extends Migration
{
    private $tableName = 'scheme_day';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'     => $this->primaryKey(),
            'number' => $this->integer()->notNull(),
            'date'   => $this->dateTime()->notNull()
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
