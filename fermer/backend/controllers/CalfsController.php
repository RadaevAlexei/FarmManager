<?php

namespace backend\controllers;

use common\helpers\DataHelper;
use common\models\Calf;
use common\models\Color;
use common\models\Groups;
use common\models\Suspension;
use frontend\assets\SuspensionAsset;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class CalfsController extends Controller
{
    /**
     * @return string
     */
    public function actionList()
    {
        $query = Calf::find()->innerJoinWith(['suit', 'calfGroup']);

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $calfs = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $this->viewDataCalfs($calfs);

        return $this->render('index', [
            'calfs' => $calfs,
            'pagination' => $pagination,
        ]);
    }

    private function viewDataCalfs(&$calfs = null)
    {
        if (empty($calfs)) {
            return;
        }

        foreach ($calfs as &$calf) {
            $this->viewListDataCalf($calf);
        }
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionDetail($number = null)
    {
        $calf = Calf::find()
            ->where(['number' => $number])
            ->innerJoinWith(['suit', 'calfGroup'])
            ->one();

        $calfSuspension = Suspension::find()
            ->where(['calf' => $number])
            ->asArray()
            ->all();

        $this->viewDataDetailCalf($calf);
        $this->viewCalfSuspension($calfSuspension);

        $map = ArrayHelper::map($calfSuspension, 'date', 'weight');
        $dates = array_keys($map);
        $weights = array_values($map);

        return $this->render('detail', [
            "calf" => $calf,
            "suspensions" => $calfSuspension,
            "dates" => $dates,
            "weights" => $weights
        ]);
    }

    private function viewDataDetailCalf(&$calf = null)
    {
        if (empty($calf)) {
            return;
        }

        $gender = ArrayHelper::getValue($calf, "gender");
        $calf["gender"] = empty($gender) ? "Тёлочка" : "Бычок";

        $calf["birthday"] = DataHelper::getDate(ArrayHelper::getValue($calf, "birthday"), "d.m.Y");

        $calf["previousWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "previousWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "previousWeighing")
        ], " / ");

        $calf["currentWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "currentWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "currentWeighing")
        ], " / ");
    }

    /**
     * @param null $suspensions
     */
    private function viewCalfSuspension(&$suspensions = null)
    {
        if (empty($suspensions)) {
            return;
        }

        foreach ($suspensions as &$suspension) {
            $suspension["date"] = DataHelper::getDate(ArrayHelper::getValue($suspension, "date"));
        }
    }

    /**
     * Преобразование данных для вывода
     * @param null $calf
     */
    private function viewListDataCalf(&$calf = null)
    {
        if (empty($calf)) {
            return;
        }

        $gender = ArrayHelper::getValue($calf, "gender");
        $calf["gender_short"] = empty($gender) ? "Т" : "Б";

        $calf["birthday"] = DataHelper::getDate(ArrayHelper::getValue($calf, "birthday"), "d.m.Y");

        $calf["previousWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "previousWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "previousWeighing")
        ], " / ");

        $calf["currentWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "currentWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "currentWeighing")
        ], " / ");
    }

    /**
     * @param null $action
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionActions($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/calfs"]);
        } else {
            if ($action == "new") {
                $model = new Calf();
                $url = Url::toRoute(['/calf/save/']);
            } else if ($action == "edit") {
                $model = Calf::find()->where(['id' => $id])->one();
                $model["birthday"] = DataHelper::getDate($model["birthday"], "Y-m-d");
                $url = Url::toRoute(['/calf/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Calf::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['/calfs']);
            }
        }

        $groups = Groups::find()->select(['name', 'employeeId', 'id'])->indexBy("id")->orderBy(['id' => SORT_ASC])->column();
        $colors = Color::find()->select(['name', 'id'])->indexBy("id")->orderBy(['id' => SORT_ASC])->column();
        $mothers = [];
        $fathers = [];

        return $this->render('calf-add', [
            "action" => $action,
            "url" => $url,
            "model" => $model,
            "groups" => $groups,
            "colors" => $colors,
            "mothers" => $mothers,
            "fathers" => $fathers,
        ]);
    }

    /**
     * @param null $action
     * @param null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveUpdate($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/calfs"]);
        } else {
            if ($action == "save") {
                $model = new Calf();
            } else if ($action == "update") {
                $model = Calf::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();

            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'CALF_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(['/calfs']);
        } else {
            return $this->render('calf-add', [
                'model' => $model,
            ]);
        }
    }
}