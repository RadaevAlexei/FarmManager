<?php

namespace backend\modules\pharmacy\models\search;

use backend\modules\pharmacy\models\Stock;
use yii\data\ActiveDataProvider;

/**
 * Class StockSearch
 * @package backend\modules\pharmacy\models\search
 *
 * @property string $name
 */
class StockSearch extends Stock
{
	/**
	 * Фильтрация складов
	 *
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Stock::find();

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

		$query->andFilterWhere(['like', 'name', $this->name]);

		return $dataProvider;
	}
}
