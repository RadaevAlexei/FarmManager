<?php

namespace common\models\search;

use common\models\Color;
use yii\data\ActiveDataProvider;

/**
 * Class ColorSearch
 * @package common\models
 */
class ColorSearch extends Color
{
    /**
     * Фильтрация должностей
     * @param $params
     */
    public function search($params)
    {
        $query = Color::find();

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
                'name',
                'short_name'
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'short_name', $this->short_name]);

        return $dataProvider;
    }
}
