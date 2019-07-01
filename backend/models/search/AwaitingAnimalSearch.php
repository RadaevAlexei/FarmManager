<?php

namespace backend\models\search;

use common\models\Animal;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class AwaitingAnimalSearch
 * @package backend\models\search
 */
class AwaitingAnimalSearch extends Animal
{
    const PAGE_SIZE = 25;

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $awaitingQuery = Animal::find()
            ->alias('a')
            ->with([
                'diagnoses'           => function (ActiveQuery $query) {
                    $query->alias('d');
                },
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->joinWith([
                                'schemeDays' => function (ActiveQuery $query) {
                                    $query->alias('sd');
                                }
                            ]);
                        }
                    ]);
                    $query->orderBy(['as.started_at' => SORT_ASC]);
                }
            ])
            ->where([
                'a.health_status' => Animal::HEALTH_STATUS_AWAITING
            ]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $awaitingQuery,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
