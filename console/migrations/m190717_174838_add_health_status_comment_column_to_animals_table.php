<?php

use yii\db\Migration;

/**
 * Handles adding health_status_comment to table `animals`.
 */
class m190717_174838_add_health_status_comment_column_to_animals_table extends Migration
{
    private $table = 'animals';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'health_status_comment', $this->text()->after('health_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'health_status_comment');
    }
}
