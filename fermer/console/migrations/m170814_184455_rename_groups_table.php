<?php

use yii\db\Migration;

/**
 * Class m170814_184455_rename_groups_table
 */
class m170814_184455_rename_groups_table extends Migration
{
    /**
     *
     */
    public function safeUp()
    {
        $this->renameTable('groups', 'group');
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->renameTable('group', 'groups');
    }
}
