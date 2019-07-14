<?php

namespace backend\modules\reproduction\models\search;

use backend\modules\reproduction\models\ContainerDuara;
use yii\data\ActiveDataProvider;

/**
 * Class ContainerDuaraSearch
 * @package backend\modules\reproduction\models\search
 */
class ContainerDuaraSearch extends ContainerDuara
{
	/**
	 * Фильтрация сосудов
	 *
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = ContainerDuara::find();

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
