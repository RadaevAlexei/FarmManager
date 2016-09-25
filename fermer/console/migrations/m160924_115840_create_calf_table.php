<?php

use yii\db\Migration;

/**
 * Handles the creation for table `calf`.
 */
class m160924_115840_create_calf_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('calf', [
            'id' => $this->primaryKey(),
            'number' => $this->string(15)->notNull(),
            'nickname' => $this->string(15),
            'group' => $this->string(15),
            'birthday' => $this->dateTime()->notNull(),
            'gender' => $this->string(1)->notNull(),
            'birthWeight' => $this->float()->notNull(),
            'previousWeighing' => $this->float(),
            'lastWeighing' => $this->float(),
            'color' => $this->integer(),
            'motherId' => $this->integer(),
            'fatherId' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('calf');
    }
}
