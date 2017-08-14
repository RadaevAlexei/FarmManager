<?php
namespace common\models\search;

use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 * @package common\models
 */
class UserSearch extends User
{
    /**
     * Название должности
     * @var
     */
    public $positionName;

    /**
     * Фильтрация пользователей
     * @param $params
     */
    public function search($params)
    {
        $query = User::find();

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
                'username',
                'gender',
                'posName' => [
                    'asc'   => ['position.name' => SORT_ASC],
                    'desc'  => ['position.name' => SORT_DESC],
                    'label' => 'adfgadfg'
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['position']);
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'username', $this->username]);

        $query->andFilterWhere([
            'gender' => $this->gender
        ]);

        $query->joinWith([
            'posName' => function ($q) {
                $q->where('position.name LIKE "%' . $this->positionName . '%"');
            }
        ]);

        return $dataProvider;
    }
}
