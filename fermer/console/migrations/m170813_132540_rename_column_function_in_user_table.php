<?php

use yii\db\Migration;
use \common\models\User;

class m170813_132540_rename_column_function_in_user_table extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->renameColumn(User::tableName(), 'function', 'position_id');
    }

    public function safeDown()
    {
        $this->renameColumn(User::tableName(), 'position_id', 'function');
    }
}
