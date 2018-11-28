<?php

namespace backend\modules\scheme\models\search;

use backend\modules\scheme\models\Action;
use backend\modules\scheme\models\ActionList;
use yii\data\ActiveDataProvider;

/**
 * Class ActionListSearch
 * @package backend\modules\scheme\models\search
 */
class ActionListSearch extends ActionList
{
    /**
     * Фильтрация списков
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ActionList::find();

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

        // Фильтрация по частичному совпадению в названии
        $query->andFilterWhere(['like', 'name', $this->name]);

        // Фильтрация по типу поля
        $query->andFilterWhere(['=', 'type', $this->type]);

        return $dataProvider;
    }
}
