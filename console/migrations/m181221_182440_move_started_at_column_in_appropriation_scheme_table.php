<?php

use yii\db\Migration;

/**
 * Class m181221_182440_move_started_at_column_in_appropriation_scheme_table
 */
class m181221_182440_move_started_at_column_in_appropriation_scheme_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $query = Yii::$app->db->createCommand("ALTER TABLE appropriation_scheme MODIFY COLUMN started_at DATE AFTER animal_id");
        $query->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $query = Yii::$app->db->createCommand("ALTER TABLE appropriation_scheme MODIFY COLUMN started_at DATE AFTER status");
        $query->execute();
    }
}
