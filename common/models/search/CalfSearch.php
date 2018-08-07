<?php

namespace common\models\search;

use common\models\Calf;
use yii\data\ActiveDataProvider;

/**
 * Class CalfSearch
 * @package common\models\search
 */
class CalfSearch extends Calf
{
    /**
     * Фильтрация поголовья
     * @param $params
     */
    public function search($params)
    {
        $query = Calf::find();

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
                'number',
                'nickname'
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'number', $this->number]);
        $query->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}
