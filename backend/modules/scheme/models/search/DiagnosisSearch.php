<?php

namespace backend\modules\scheme\models\search;

use yii\data\ActiveDataProvider;
use backend\modules\scheme\models\Diagnosis;

/**
 * Class DiagnosisSearch
 * @package common\models\search
 */
class DiagnosisSearch extends Diagnosis
{
	/**
	 * Фильтрация диагнозов
	 *
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Diagnosis::find();

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
