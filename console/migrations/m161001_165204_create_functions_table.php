<?php

use yii\db\Migration;

/**
 * Handles the creation for table `functions`.
 */
class m161001_165204_create_functions_table extends Migration
{
    /**
     * Должность
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('functions', [
            'id' => $this->primaryKey(),                             // id
            'name' => $this->string(50)->unique()->notNull(),        // Название должности
            'short_name' => $this->string(30)->unique()->notNull(),  // Короткое название должности
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
