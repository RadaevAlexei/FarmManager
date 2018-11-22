<?php

namespace backend\modules\scheme\models\search;

use backend\modules\scheme\models\Preparation;
use yii\data\ActiveDataProvider;

/**
 * Class PreparationSearch
 * @package backend\modules\scheme\models\search
 */
class PreparationSearch extends Preparation
{
	/**
	 * Фильтрация препаратов
	 *
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Preparation::find();

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
