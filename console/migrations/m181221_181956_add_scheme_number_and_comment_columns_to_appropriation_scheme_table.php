<?php

use yii\db\Migration;

/**
 * Handles adding scheme_number_and_comment to table `appropriation_scheme`.
 */
class m181221_181956_add_scheme_number_and_comment_columns_to_appropriation_scheme_table extends Migration
{
    private $tableName = 'appropriation_scheme';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'scheme_number', $this->integer()->notNull()->after('scheme_id'));
        $this->addColumn($this->tableName, 'comment', $this->string()->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'scheme_number');
        $this->dropColumn($this->tableName, 'comment');
    }
}
