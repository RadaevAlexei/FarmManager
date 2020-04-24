<?php

use yii\db\Migration;

/**
 * Class m200423_130856_change_email_column_in_user_table
 */
class m200423_130856_change_email_column_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('email', 'user');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
