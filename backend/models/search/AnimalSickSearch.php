<?php

namespace backend\models\search;

use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\AppropriationScheme;
use common\models\Animal;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class ActionHistorySearch
 * @package backend\modules\scheme\models\search
 */
class AnimalSickSearch extends Animal
{
    const PAGE_SIZE = 25;

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $historyQuery = Animal::find()
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
                    $query->andFilterWhere(['as.status' => AppropriationScheme::STATUS_IN_PROGRESS]);
                    $query->orderBy(['as.started_at' => SORT_ASC]);
                }
            ])
            ->where([
                'a.health_status' => Animal::HEALTH_STATUS_SICK
            ]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $historyQuery,
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
