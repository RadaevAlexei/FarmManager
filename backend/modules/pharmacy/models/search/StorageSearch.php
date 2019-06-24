<?php

namespace backend\modules\pharmacy\models\search;

use backend\modules\pharmacy\models\Storage;
use yii\data\ActiveDataProvider;

/**
 * Class StorageSearch
 * @package backend\modules\pharmacy\models\search
 */
class StorageSearch extends Storage
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
		$query = Storage::find();

		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'pagination' => [
				'pageSize' => self::PAGE_SIZE,
			],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		return $dataProvider;
	}
}
