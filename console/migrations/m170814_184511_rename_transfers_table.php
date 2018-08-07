<?php

use yii\db\Migration;

/**
 * Class m170814_184511_rename_transfers_table
 */
class m170814_184511_rename_transfers_table extends Migration
{
    /**
     *
     */
    public function safeUp()
    {
        $this->renameTable('transfers', 'transfer');
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->renameTable('transfer', 'transfers');
    }
}
