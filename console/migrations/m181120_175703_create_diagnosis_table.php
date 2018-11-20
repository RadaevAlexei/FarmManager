<?php

use yii\db\Migration;

/**
 * Handles the creation of table `diagnosis`.
 */
class m181120_175703_create_diagnosis_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('diagnosis', [
			'id'   => $this->primaryKey(),
			'name' => $this->string(255)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('diagnosis');
	}
}
