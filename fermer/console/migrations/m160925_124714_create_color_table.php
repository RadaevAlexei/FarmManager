<?php

use yii\db\Migration;

/**
 * Handles the creation for table `color`.
 */
class m160925_124714_create_color_table extends Migration
{
    /**
     * Масть
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('color', [
            'id' => $this->primaryKey(),                            // id
            'name' => $this->string(30)->unique()->notNull(),       // Наименование масти
            'short_name' => $this->string(15)->unique()->notNull()  // Короткое наименование масти
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
