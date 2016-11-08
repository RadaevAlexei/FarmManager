<?php

namespace backend\controllers;

use common\models\Calf;
use common\models\Color;
use common\models\Groups;
use yii\data\Pagination;
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
        $query = Calf::find();

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
            $this->viewDataCalf($calf);
        }

    }

    private function viewDataCalf(&$calf = null)
    {
        if (empty($calf)) {
            return;
        }
        $calf["birthday"] = date("d/m/Y", ArrayHelper::getValue($calf, "birthday", null));
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
                $model["birthday"] = date("Y-m-d", $model["birthday"]);
                $url = Url::toRoute(['/calf/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Calf::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['/calfs']);
            }
        }

        $groups = Groups::find()->select(['name', 'employeeId', 'id'])->orderBy(['id' => SORT_ASC])->column();
        $colors = Color::find()->select(['name', 'id'])->orderBy(['id' => SORT_ASC])->column();
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
            $model->previousWeighing = $model->birthWeight;
            $model->currentWeighingDate = $model->birthWeight;
            $model->currentWeighing = $model->birthWeight;
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