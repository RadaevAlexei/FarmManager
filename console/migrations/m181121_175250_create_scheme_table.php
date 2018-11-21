<?php

use yii\db\Migration;

/**
 * Handles the creation of table `scheme`.
 */
class m181121_175250_create_scheme_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('scheme', [
			'id'           => $this->primaryKey(),
			'name'         => $this->string()->notNull(),
			'created_by'   => $this->integer(),
			'created_at'   => $this->integer(),
			'diagnosis_id' => $this->integer()->notNull(),
		]);

		$this->addForeignKey('fk-diagnosis', 'scheme', 'diagnosis_id', 'diagnosis', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('fk-diagnosis', 'scheme');
		$this->dropTable('scheme');
	}
}
