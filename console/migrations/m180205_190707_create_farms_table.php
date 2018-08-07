<?php

use yii\db\Migration;
use \common\models\Farm;

/**
 * Handles the creation for table `farms`.
 */
class m180205_190707_create_farms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(Farm::tableName(), [
            'id'   => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Farm::tableName());
    }
}
