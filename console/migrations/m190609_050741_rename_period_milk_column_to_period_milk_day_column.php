<?php

use yii\db\Migration;

/**
 * Class m190609_050741_rename_period_milk_column_to_period_milk_day_column
 */
class m190609_050741_rename_period_milk_column_to_period_milk_day_column extends Migration
{
    private $table = 'preparation';

    private $oldColumn = 'period_milk';
    private $newColumn = 'period_milk_day';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn($this->table, $this->oldColumn, $this->newColumn);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn($this->table, $this->newColumn, $this->oldColumn);
    }
}
