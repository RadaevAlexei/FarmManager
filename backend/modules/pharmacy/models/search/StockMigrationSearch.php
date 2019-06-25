<?php

namespace backend\modules\pharmacy\models\search;

use backend\modules\pharmacy\models\StockMigration;
use yii\data\ActiveDataProvider;

/**
 * Class StockMigrationSearch
 * @package backend\modules\pharmacy\models\search
 */
class StockMigrationSearch extends StockMigration
{
    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
	public function search($params)
	{
		$query = StockMigration::find();

		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => self::PAGE_SIZE,
			],
		]);

		/**
		 * Настраиваем параметры сортировки
		 * Важно: должна быть выполнена раньше $this->load($params)
		 */
		$dataProvider->setSort([
			'attributes' => [
				'name'
			]
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		return $dataProvider;
	}
}
