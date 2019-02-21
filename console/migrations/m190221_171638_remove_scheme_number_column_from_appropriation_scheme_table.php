<?php

use yii\db\Migration;

/**
 * Class m190221_171638_remove_scheme_number_column_from_appropriation_scheme_table
 */
class m190221_171638_remove_scheme_number_column_from_appropriation_scheme_table extends Migration
{
    private $tableName = 'appropriation_scheme';

    private $column = 'scheme_number';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, $this->column);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn($this->tableName, $this->column, $this->integer()->notNull()->after('scheme_id'));
    }
}
