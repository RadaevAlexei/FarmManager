<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seed_bull`.
 */
class m190709_074737_create_seed_bull_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('seed_bull', [
            'id' => $this->primaryKey(),
            'nickname' => $this->string(255),
            'number_1' => $this->string(255),
            'number_2' => $this->string(255),
            'number_3' => $this->string(255),
            'contractor' => $this->integer(),
            'breed' => $this->integer(),
            'color_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('seed_bull');
    }
}
