<?php

namespace backend\modules\scheme\models\search;

use backend\modules\scheme\models\Scheme;
use yii\data\ActiveDataProvider;

/**
 * Class SchemeSearch
 * @package backend\modules\scheme\models\search
 */
class SchemeSearch extends Scheme
{
    /**
     * Фильтрация схем лечения
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Scheme::find()
            ->alias('s')
            ->joinWith(['diagnosis', 'createdBy'])
            ->where(['s.status' => self::STATUS_ACTIVE]);

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
