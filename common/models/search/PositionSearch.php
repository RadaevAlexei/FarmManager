<?php
namespace common\models\search;

use common\models\Position;
use yii\data\ActiveDataProvider;

/**
 * Class PositionSearch
 * @package common\models
 */
class PositionSearch extends Position
{
    /**
     * Фильтрация должностей
     * @param $params
     */
    public function search($params)
    {
        $query = Position::find();

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
