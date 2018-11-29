<?php

use yii\db\Migration;

/**
 * Handles the creation of table `scheme_day_groups_action_link`.
 */
class m181129_193703_create_scheme_day_groups_action_link_table extends Migration
{
    private $tableName = 'scheme_day_groups_action_link';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'               => $this->primaryKey(),
            'scheme_day_id'    => $this->integer()->notNull(),
            'groups_action_id' => $this->integer()->notNull(),
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
