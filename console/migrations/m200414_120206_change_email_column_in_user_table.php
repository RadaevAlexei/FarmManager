<?php

use yii\db\Migration;

/**
 * Class m200414_120206_change_email_column_in_user_table
 */
class m200414_120206_change_email_column_in_user_table extends Migration
{
    private $tableName = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'email', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'email', $this->string(255)->notNull());
    }

}
