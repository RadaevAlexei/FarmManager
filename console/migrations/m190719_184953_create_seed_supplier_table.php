<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seed_supplier`.
 */
class m190719_184953_create_seed_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('seed_supplier', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('seed_supplier');
    }
}
