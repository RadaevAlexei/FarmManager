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
    public $posName;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['countryName'], 'safe']
        ];
    }

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
                'firstName',
                'lastName',
                'middleName',
                'username',
                'gender',
                'posName' => [
                    'asc'  => ['position.name' => SORT_ASC],
                    'desc' => ['position.name' => SORT_DESC],
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['pos']);
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'firstName', $this->firstName]);
        $query->andFilterWhere(['like', 'lastName', $this->lastName]);
        $query->andFilterWhere(['like', 'middleName', $this->middleName]);
        $query->andFilterWhere(['like', 'username', $this->username]);

        $query->andFilterWhere([
            'gender' => $this->gender
        ]);

        $query->joinWith([
            'pos' => function ($q) {
                $q->where('position.name LIKE "%' . $this->posName . '%"');
            }
        ]);

        return $dataProvider;
    }
}
