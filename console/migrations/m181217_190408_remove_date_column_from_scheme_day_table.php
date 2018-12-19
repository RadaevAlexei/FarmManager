<?php

use yii\db\Migration;

/**
 * Class m181217_190408_remove_date_column_from_scheme_day_table
 */
class m181217_190408_remove_date_column_from_scheme_day_table extends Migration
{
    private $tableName = 'scheme_day';
    private $column = 'date';

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
        $this->addColumn($this->tableName, $this->column, $this->dateTime()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181217_190408_remove_date_column_from_scheme_day_table cannot be reverted.\n";

        return false;
    }
    */
}
