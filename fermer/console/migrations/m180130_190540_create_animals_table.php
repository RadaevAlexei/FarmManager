<?php

use yii\db\Migration;
use \common\models\Animal;

/**
 * Handles the creation for table `animals`.
 */
class m180130_190540_create_animals_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(Animal::tableName(), [
            'id'                     => $this->primaryKey(),
            'cowshed_id'             => $this->integer()->unsigned(),
            'box'                    => $this->integer()->unsigned(),
            'nickname'               => $this->string(),
            'label'                  => $this->string()->notNull(),
            'farm_id'                => $this->integer()->unsigned(),
            'birthday'               => $this->dateTime()->notNull(),
            'sex'                    => $this->integer()->unsigned()->notNull(),
            'birth_weight'           => $this->double(),
            'color'                  => $this->integer()->unsigned(),
            'mother_id'              => $this->integer()->unsigned(),
            'father_id'              => $this->integer()->unsigned(),
            'group_id'               => $this->integer()->unsigned(),
            'physical_state'         => $this->integer()->unsigned()->notNull(),
            'status'                 => $this->integer()->unsigned(),
            'rectal_examination'     => $this->integer()->unsigned(),
            'previous_weighing_date' => $this->dateTime(),
            'previous_weighing'      => $this->double(),
            'current_weighing_date'  => $this->dateTime(),
            'current_weighing'       => $this->double(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Animal::tableName());
    }
}
