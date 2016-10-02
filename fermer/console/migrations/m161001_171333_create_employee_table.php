<?php

use yii\db\Migration;

/**
 * Handles the creation for table `employee`.
 */
class m161001_171333_create_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('employee', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(20)->notNull(),
            'lastName' => $this->string(20)->notNull(),
            'middleName' => $this->string(20)->notNull(),
            'birthday' => $this->timestamp(),
            'gender' => $this->smallInteger()->notNull(),
            'functionId' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('employee');
    }
}
