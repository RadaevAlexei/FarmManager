<?php

namespace common\models\search;

use common\models\AnimalGroup;
use yii\data\ActiveDataProvider;

/**
 * Class AnimalGroupSearch
 * @package common\models\search
 */
class AnimalGroupSearch extends AnimalGroup
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AnimalGroup::find();

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
                'name',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
