<?php

namespace common\models\search;

use common\models\Transfer;
use yii\data\ActiveDataProvider;

/**
 * Class TransferSearch
 * @package common\models\search
 */
class TransferSearch extends Transfer
{
    /**
     * Фильтрация переводов
     * @param $params
     */
    public function search($params)
    {
        $query = Transfer::find();

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
                'groupFromId',
//                'groupToId',
//                'date',
//                'calf',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'groupFromId' => $this->groupFromId
        ]);

        return $dataProvider;
    }
}
