<?php

use yii\db\Migration;

/**
 * Class m190624_192511_add_columns_to_preparation_table
 */
class m190624_192511_add_columns_to_preparation_table extends Migration
{
    private $table = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'price', $this->double()->after('danger_class'));
        $this->addColumn($this->table, 'measure', $this->integer()->after('name'));
        $this->addColumn($this->table, 'volume', $this->double()->after('measure'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'price');
        $this->dropColumn($this->table, 'measure');
        $this->dropColumn($this->table, 'volume');
    }
}
