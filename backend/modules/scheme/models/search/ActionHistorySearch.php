<?php

namespace backend\modules\scheme\models\search;

use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\Scheme;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class ActionHistorySearch
 * @package backend\modules\scheme\models\search
 */
class ActionHistorySearch extends ActionHistory
{
    const PAGE_SIZE = 25;

    /**
     * Фильтрация схем лечения
     *
     * @param $params
     *
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        /** @var ActionHistory[] $history */
        $history = ActionHistory::find()
            ->alias('ah')
            ->select(['ah.*', 'as.scheme_id', 'as.animal_id'])
            ->joinWith([
                'groupsAction',
                'action',
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'animal' => function (ActiveQuery $query) {
                            $query->alias('a');
                            $query->joinWith(['animalGroup']);
                        },
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->joinWith(['diagnosis']);
                        }
                    ]);
                },
            ])
            ->where([
                'ah.scheme_day_at' => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d')
            ])->all();

        $result = [];
        foreach ($history as $action) {
            if (array_key_exists(ArrayHelper::getValue($action, "appropriationScheme.scheme.id"), $result)) {
                if (!in_array(ArrayHelper::getValue($action, "appropriationScheme.animal.nickname"),
                    $result[ArrayHelper::getValue($action, "appropriationScheme.scheme.id")])
                ) {
                    $result[ArrayHelper::getValue($action, "appropriationScheme.scheme.id")][] =
                        ArrayHelper::getValue($action, "appropriationScheme.animal.nickname");
                }
            } else {
                $result[ArrayHelper::getValue($action, "appropriationScheme.scheme.id")][] =
                    ArrayHelper::getValue($action, "appropriationScheme.animal.nickname");
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $result,
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
