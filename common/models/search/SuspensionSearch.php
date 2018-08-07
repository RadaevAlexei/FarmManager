<?php

namespace common\models\search;

use common\models\Suspension;
use yii\data\ActiveDataProvider;

/**
 * Class SuspensionSearch
 * @package common\models\search
 */
class SuspensionSearch extends Suspension
{
    /**
     * Фильтрация перевесок
     * @param $params
     */
    public function search($params)
    {
        $query = Suspension::find();

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
                'weight',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'weight' => $this->weight
        ]);

        return $dataProvider;
    }
}
