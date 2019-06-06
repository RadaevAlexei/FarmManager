<?php

use yii\db\Migration;

/**
 * Handles adding classification to table `preparation`.
 */
class m190606_195506_add_classification_columns_to_preparation_table extends Migration
{
    private $table = 'preparation';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'classification', $this->integer()->after('category'));
        $this->addColumn($this->table, 'beta', $this->integer()->after('classification'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'classification');
        $this->dropColumn($this->table, 'beta');
    }
}
