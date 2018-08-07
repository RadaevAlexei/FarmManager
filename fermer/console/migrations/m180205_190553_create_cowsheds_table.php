<?php

use yii\db\Migration;
use \common\models\Cowshed;

/**
 * Handles the creation for table `cowsheds`.
 */
class m180205_190553_create_cowsheds_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(Cowshed::tableName(), [
            'id'   => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Cowshed::tableName());
    }
}
