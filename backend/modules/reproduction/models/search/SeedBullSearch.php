<?php

namespace backend\modules\reproduction\models\search;

use backend\modules\reproduction\models\SeedBull;
use yii\data\ActiveDataProvider;

/**
 * Class SeedBullSearch
 * @package backend\modules\reproduction\models\search
 */
class SeedBullSearch extends SeedBull
{
    /**
     * Фильтрация семени
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SeedBull::find();

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
                'nickname'
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->nickname]);

        return $dataProvider;
    }
}
