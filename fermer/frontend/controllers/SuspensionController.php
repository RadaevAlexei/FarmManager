<?php

namespace frontend\controllers;

use common\models\Calf;
use common\models\Groups;
use common\models\Suspension;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SuspensionController
 * @package frontend\controllers
 */
class SuspensionController extends Controller
{
    public function actionIndex()
    {
        $groups = Groups::find()->select(['id', 'name'])->all();

        return $this->render("index", [
            "groups" => $groups
        ]);
    }

    /**
     * Подгрузка доступных дат перевесок для выбранной группы
     * @param null $groupId
     * @return string
     */
    public function actionLoadDates($groupId = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $dates = Suspension::find()
            ->where(["group" => $groupId])
            ->select(['date'])
            ->asArray()
            ->all();

        if (!empty($dates)) {
            foreach ($dates as &$item) {
                $item = date("d/m/Y", $item["date"]);
            }

        }

        return $dates;
    }

    /**
     * Подгрузка перевесок для выбранной группы и даты
     * @param null $groupId
     * @param null $date
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionLoadSuspensions($groupId = null, $date = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $time = strtotime(str_replace("/", "-", $date));
        $suspensions = Suspension::find()
            ->where([
                "group" => $groupId,
                "date" => $time
            ])->all();

        $result = $this->format($suspensions);

        return $this->renderAjax("suspensions", [
            "suspensions" => $suspensions,
            "result" => $result
        ]);
    }

    private function format($suspensions)
    {
        if (empty($suspensions)) {
            return [
                "dates" => [],
                "weights" => []
            ];
        }

        $dates = [];
        $weights = [];

        foreach ($suspensions as $suspension) {
            $dates[] = date("d/m/Y", $suspension["date"]);
            $weights[] = $suspension["weight"];
        }

        return [
            "dates" => $dates,
            "weights" => $weights
        ];
    }
}