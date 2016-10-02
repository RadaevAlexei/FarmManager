<?php

use yii\db\Migration;

/**
 * Handles the dropping for table `functions`.
 */
class m161002_115223_drop_functions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('functions');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->createTable('functions', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique()
        ]);
    }
}
