<?php

use yii\db\Migration;

/**
 * Class m190324_075611_remove_columns_from_action_history_table
 */
class m190324_075611_remove_columns_from_action_history_table extends Migration
{
    private $tableName = 'action_history';
    private $refTableScheme = 'scheme';
    private $refTableDay = 'scheme_day';
    private $refTableAppropriationScheme = 'appropriation_scheme';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-scheme', $this->tableName);
        $this->dropColumn($this->tableName, 'scheme_id');

        $this->dropForeignKey('fk-scheme_day', $this->tableName);
        $this->dropColumn($this->tableName, 'scheme_day_id');

        $this->addColumn($this->tableName, 'appropriation_scheme_id', $this->integer()->notNull()->after('id'));
        $this->addForeignKey('fk-appropriation_scheme', $this->tableName, 'appropriation_scheme_id', $this->refTableAppropriationScheme, 'id');

        $this->alterColumn($this->tableName, 'execute_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-appropriation_scheme', $this->tableName);
        $this->dropColumn($this->tableName, 'appropriation_scheme_id');

        $this->addColumn($this->tableName, 'scheme_id', $this->integer()->notNull()->after('id'));
        $this->addColumn($this->tableName, 'scheme_day_id', $this->integer()->notNull()->after('scheme_id'));

        $this->addForeignKey('fk-scheme', $this->tableName, 'scheme_id', $this->refTableScheme, 'id');
        $this->addForeignKey('fk-scheme_day', $this->tableName, 'scheme_day_id', $this->refTableDay, 'id');

        $this->alterColumn($this->tableName, 'execute_at', $this->dateTime()->notNull());
    }
}
