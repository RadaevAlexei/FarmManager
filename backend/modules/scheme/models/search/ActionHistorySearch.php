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
                'ah.scheme_day_at' => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d'),
                'ah.status' => ActionHistory::STATUS_NEW
            ])->all();

        $schemes = [];
        foreach ($history as $action) {
            $schemeId = ArrayHelper::getValue($action, "appropriationScheme.scheme.id");
            $animalName = ArrayHelper::getValue($action, "appropriationScheme.animal.nickname");

            if (array_key_exists($schemeId, $schemes)) {
                if (!in_array($animalName, $schemes[$schemeId]["animals"])) {
                    $schemes[$schemeId]["animals"][] = $animalName;
                }
            } else {
                $schemes[$schemeId]["animals"][] = $animalName;
                $schemes[$schemeId]["scheme_name"] = ArrayHelper::getValue($action, "appropriationScheme.scheme.name");
            }
        }

        $result = [];
        foreach ($schemes as $schemeId => $data) {
            $result[] = [
                'scheme_id'   => $schemeId,
                'scheme_name' => $data["scheme_name"],
                'animals'     => $data["animals"],
            ];
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
