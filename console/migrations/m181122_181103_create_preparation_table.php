<?php

use yii\db\Migration;

/**
 * Handles the creation of table `preparation`.
 */
class m181122_181103_create_preparation_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('preparation', [
			'id'           => $this->primaryKey(),
			'name'         => $this->string()->notNull(),
			'receipt_date' => $this->dateTime(),
			'packing'      => $this->integer()->notNull(),
			'volume'       => $this->double()->notNull(),
			'price'        => $this->double()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('preparation');
	}
}
