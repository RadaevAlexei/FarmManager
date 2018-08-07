<?php

namespace common\models\search;

use common\models\Cowshed;
use yii\data\ActiveDataProvider;

/**
 *
 * Class CowshedSearch
 * @package common\models\search
 */
class CowshedSearch extends Cowshed
{
    /**
     * Фильтрация коровников
     *
     * @param $params
     */
    public function search($params)
    {
        $query = Cowshed::find();

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
