<?php

use yii\db\Migration;

/**
 * Class m200511_054327_rename_color_column_in_animals_table
 */
class m200511_054327_rename_color_column_in_animals_table extends Migration
{
    private $tableName = 'animals';

    private $oldColumn = 'color';
    private $newColumn = 'color_id';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn($this->tableName, $this->oldColumn, $this->newColumn);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn($this->tableName, $this->newColumn, $this->oldColumn);
    }

}
