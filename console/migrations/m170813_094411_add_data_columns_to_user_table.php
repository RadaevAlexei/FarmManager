<?php

use yii\db\Migration;
use \common\models\User;

/**
 * Handles adding data to table `user`.
 */
class m170813_094411_add_data_columns_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(User::tableName(), 'firstName', $this->string());
        $this->addColumn(User::tableName(), 'lastName', $this->string());
        $this->addColumn(User::tableName(), 'middleName', $this->string());
        $this->addColumn(User::tableName(), 'birthday', $this->dateTime());
        $this->addColumn(User::tableName(), 'gender', $this->integer());
        $this->addColumn(User::tableName(), 'function', $this->integer()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn(User::tableName(), 'firstName');
        $this->dropColumn(User::tableName(), 'lastName');
        $this->dropColumn(User::tableName(), 'middleName');
        $this->dropColumn(User::tableName(), 'birthday');
        $this->dropColumn(User::tableName(), 'gender');
        $this->dropColumn(User::tableName(), 'function');
    }
}
