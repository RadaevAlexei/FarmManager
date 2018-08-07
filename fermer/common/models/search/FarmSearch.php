<?php

namespace common\models\search;

use common\models\Cowshed;
use common\models\Farm;
use yii\data\ActiveDataProvider;

/**
 * Class FarmSearch
 * @package common\models\search
 */
class FarmSearch extends Farm
{
    /**
     * Фильтрация ферм
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Farm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
