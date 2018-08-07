<?php

use yii\db\Migration;
use \common\models\Suspension;

/**
 * Handles the creation for table `suspension`.
 */
class m161016_090019_create_suspension_table extends Migration
{
    /**
     *
     */
    public function up()
    {
        $this->createTable(Suspension::tableName(), [
            'id'     => $this->primaryKey(),             // Идентификатор
            'animal' => $this->integer()->notNull(),     // Животное
            'date'   => $this->dateTime()->notNull(),    // Дата взвешивания
            'weight' => $this->float()->notNull()        // Вес
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Suspension::tableName());
    }
}
