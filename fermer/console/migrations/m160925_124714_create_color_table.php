<?php

use yii\db\Migration;

/**
 * Handles the creation for table `color`.
 */
class m160925_124714_create_color_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('color', [
            'id' => $this->primaryKey(),
            'name' => $this->string(25)->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('color');
    }
}
