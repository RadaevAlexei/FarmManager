<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%calving}}`.
 */
class m200928_192648_add_number_column_to_calving_table extends Migration
{
    private $tableName = 'calving';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            $this->tableName,
            'number',
            $this->smallInteger()->after('animal_id')->notNull()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'number');
    }
}
