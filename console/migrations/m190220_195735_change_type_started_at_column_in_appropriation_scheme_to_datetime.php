<?php

use yii\db\Migration;

/**
 * Class m190220_195735_change_type_started_at_column_in_appropriation_scheme_to_datetime
 */
class m190220_195735_change_type_started_at_column_in_appropriation_scheme_to_datetime extends Migration
{
    private $tableName = 'appropriation_scheme';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'started_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'started_at', $this->date());
    }
}
