<?php

use yii\db\Migration;

class m170813_125402_rename_functions_table extends Migration
{
    public function safeUp()
    {
        $this->renameTable('functions', 'position');
    }

    public function safeDown()
    {
        $this->renameTable('position', 'functions');
    }
}