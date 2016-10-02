<?php

use yii\db\Migration;

/**
 * Handles the creation for table `groups`.
 */
class m161001_194056_create_groups_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('groups', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'directorId' => $this->integer()->notNull(),
            'mainZootechnicianId' => $this->integer()->notNull(),
            'accountantId' => $this->integer()->notNull(),
            'calfEmployeeId' => $this->integer()->notNull(),
            'directorSecurityId' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'deleted_at' => $this->timestamp(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('groups');
    }
}
