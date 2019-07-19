<?php

namespace backend\modules\reproduction\models\search;

use backend\modules\reproduction\models\SeedSupplier;
use yii\data\ActiveDataProvider;

/**
 * Class SeedSupplierSearch
 * @package backend\modules\reproduction\models\search
 */
class SeedSupplierSearch extends SeedSupplier
{
	/**
	 * Фильтрация поставщиков
	 *
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = SeedSupplier::find();

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
