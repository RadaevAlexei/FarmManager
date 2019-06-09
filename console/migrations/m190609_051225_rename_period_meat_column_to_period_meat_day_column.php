<?php

use yii\db\Migration;

/**
 * Class m190609_051225_rename_period_meat_column_to_period_meat_day_column
 */
class m190609_051225_rename_period_meat_column_to_period_meat_day_column extends Migration
{
    private $table = 'preparation';

    private $oldColumn = 'period_meat';
    private $newColumn = 'period_meat_day';

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
