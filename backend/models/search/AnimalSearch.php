<?php

namespace backend\models\search;

use common\models\Animal;
use yii\data\ActiveDataProvider;

/**
 * Class AnimalSearch
 * @package backend\models\search
 */
class AnimalSearch extends Animal
{
    const PAGE_SIZE = 25;

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Animal::find();
        
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        /**
         * Поиск по номеру ошейника
         */
        $query->andFilterWhere(['like', 'collar', $this->collar]);

        /**
         * Поиск по бирке
         */
        $query->andFilterWhere(['like', 'label', $this->label]);

        /**
         * Поиск по кличке
         */
        $query->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}
