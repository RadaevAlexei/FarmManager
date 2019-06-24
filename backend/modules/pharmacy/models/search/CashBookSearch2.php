<?php

namespace backend\modules\pharmacy\models\search;

use backend\modules\pharmacy\models\CashBook;
use yii\data\ActiveDataProvider;

/**
 * Class CashBookSearch2
 * @package backend\modules\pharmacy\models\search
 */
class CashBookSearch2 extends CashBook
{
	/**
	 * Фильтрация
	 *
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = CashBook::find();

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
