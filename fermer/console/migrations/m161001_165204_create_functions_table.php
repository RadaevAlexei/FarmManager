<?php

use yii\db\Migration;

/**
 * Handles the creation for table `functions`.
 */
class m161001_165204_create_functions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('functions', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('functions');
    }
}
