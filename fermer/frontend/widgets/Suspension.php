<?php

namespace frontend\widgets;


use common\models\Groups;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class Suspension
 * @package frontend\widgets
 */
class Suspension extends Widget
{
    /**
     * @var
     */
    public $view;

    /**
     * @var
     */
    public $data;

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
//        SuspensionAsset::register($this->view);

        $values = [];
        $mainTable = $this->generateSuspensionTable($this->data, $values)["content"];
        $baseResultTable = $this->generateBaseResultTable($values);
        $movedTable = $this->generateMovedCalfTable();
        $leadershipTable = $this->generateLeadershipTable(2);

        return $this->render($this->view, [
            "mainTable" => $mainTable,
            "baseResultTable" => $baseResultTable,
            "movedTable" => $movedTable,
            "leadershipTable" => $leadershipTable,
        ]);
    }

    /**
     * @param null $data
     * @return string
     */
    private function generateSuspensionTable($data = null, $values)
    {
        $content = [];

        if (empty($data)) {
            $content[] = Html::beginTag("tr");
            $content[] = Html::tag("td", "Нет ничего", ['align' => 'center', 'colspan' => 17]);
            $content[] = Html::endTag("tr");
        } else {
            foreach ($data as $index => $calf) {
                $content[] = Html::beginTag("tr", ['data-id' => $calf["id"], 'style' => 'cursor: pointer']);
                $content[] = Html::tag("td", $index + 1);
                $content[] = Html::tag("td", ArrayHelper::getValue($calf, "number", ""));

                $birthday = (!empty($calf["birthday"]) ? date("d/m/Y", strtotime($calf["birthday"])) : "");
                $content[] = Html::tag("td", $birthday);

                $content[] = Html::tag("td", ArrayHelper::getValue($calf, "birthWeight", ""));
                $content[] = Html::tag("td", ArrayHelper::getValue($calf, "gender", ""));
                $content[] = Html::tag("td", "");
                $content[] = Html::tag("td", ArrayHelper::getValue($calf, "previousWeighing", ""));
                $content[] = Html::tag("td", "");
                $content[] = Html::tag("td", ArrayHelper::getValue($calf, "lastWeighing", ""));

                $now = new \DateTime("now");
                $birthDay = new \DateTime($calf["birthday"]);
                $interval = date_diff($now, $birthDay);
                $content[] = Html::tag("td", $interval->d);
                $content[] = Html::tag("td", $interval->m);
                $content[] = Html::tag("td", $interval->y);

                $feedDays = date_diff(new \DateTime("tomorrow"), new \DateTime("now"))->d;
                $content[] = Html::tag("td", $feedDays);

                $weightGain = $calf["lastWeighing"] - $calf["previousWeighing"];
                $content[] = Html::tag("td", $weightGain);

                $averageDailyGain = !empty($feedDays) ? $weightGain / $feedDays : "";
                $content[] = Html::tag("td", $averageDailyGain);
                $content[] = Html::tag("td", "");
                $content[] = Html::tag("td", "");
                $content[] = Html::endTag("tr");
            }
        }

        return [
            "content" => implode("", $content),
            "values" => $values
        ];
    }

    /**
     *
     */
    private function generateBaseResultTable($values = null)
    {
        $content = [];

        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Итого взвешано за месяц");
        $content[] = Html::tag("td", 82);
        $content[] = Html::tag("td", 0);
        $content[] = Html::tag("td", 82);
        $content[] = Html::endTag("tr");

        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Среднесуточный привес в среднем на группу");
        $content[] = Html::tag("td", 0.379);
        $content[] = Html::tag("td", 0);
        $content[] = Html::tag("td", 0.379);
        $content[] = Html::endTag("tr");

        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Валовый привес");
        $content[] = Html::tag("td", 776);
        $content[] = Html::tag("td", 0);
        $content[] = Html::tag("td", 776);
        $content[] = Html::endTag("tr");

        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Корм. Дни / сумма");
        $content[] = Html::tag("td", 2049);
        $content[] = Html::tag("td", 0);
        $content[] = Html::tag("td", 2049);
        $content[] = Html::endTag("tr");

        return implode("", $content);
    }

    /**
     * @return string
     */
    private function generateMovedCalfTable($data = null)
    {
        $content = [];

        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Количество");
        $count = !empty($data) ? 25 : 0;
        $content[] = Html::tag("td", $count);
        $content[] = Html::endTag("tr");

        return implode("", $content);
    }

    /**
     * @return string
     */
    private function generateLeadershipTable($id)
    {
        $content = [];

        $group = Groups::find()->where(['id' => $id])->one();

        // Выводим исполнительного директора
        $fioDirector = implode(" ", [$group->director["firstName"], $group->director["lastName"], $group->director["middleName"]]);
        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Исп. Директор ООО \"Агро-Нептун\"");
        $content[] = Html::tag(
            "td",
            Html::a($fioDirector, Url::toRoute(['/employee/detail', 'id' => $group->director["id"]]))
        );
        $content[] = Html::endTag("tr");

        // Выводим главного зоотехника
        $fioZootechnician = implode(" ", [$group->zootechnician["firstName"], $group->zootechnician["lastName"], $group->zootechnician["middleName"]]);
        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Гл. зоотехник");
        $content[] = Html::tag(
            "td",
            Html::a($fioZootechnician, Url::toRoute(['/employee/detail', 'id' => $group->zootechnician["id"]]))
        );
        $content[] = Html::endTag("tr");

        // Выводим бухгалтера
        $fioAccountant = implode(" ", [$group->accountant["firstName"], $group->accountant["lastName"], $group->accountant["middleName"]]);
        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Бухгалтер");
        $content[] = Html::tag(
            "td",
            Html::a($fioAccountant, Url::toRoute(['/employee/detail', 'id' => $group->accountant["id"]]))
        );
        $content[] = Html::endTag("tr");

        // Выводим телятника(цу)
        $fioCalfEmployee = implode(" ", [$group->calfEmployee["firstName"], $group->calfEmployee["lastName"], $group->calfEmployee["middleName"]]);
        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Телятник(ца)");
        $content[] = Html::tag(
            "td",
            Html::a($fioCalfEmployee, Url::toRoute(['/employee/detail', 'id' => $group->calfEmployee["id"]]))
        );
        $content[] = Html::endTag("tr");

        // Выводим начальника службы безопасности
        $fioDirectorSecurity = implode(" ", [$group->directorSecurity["firstName"], $group->directorSecurity["lastName"], $group->directorSecurity["middleName"]]);
        $content[] = Html::beginTag("tr");
        $content[] = Html::tag("td", "Нач. службы безопасности");
        $content[] = Html::tag(
            "td",
            Html::a($fioDirectorSecurity, Url::toRoute(['/employee/detail', 'id' => $group->directorSecurity["id"]]))
        );
        $content[] = Html::endTag("tr");

        return implode("", $content);
    }

}