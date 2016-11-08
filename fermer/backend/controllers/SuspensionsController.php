<?php

namespace backend\controllers;

use common\models\Calf;
use common\models\Suspension;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class SuspensionsController extends Controller
{
    /**
     * @return string
     */
    public function actionList()
    {
        $query = Suspension::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $suspensions = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $this->viewDataSuspensions($suspensions);

        return $this->render('index', [
            'suspensions' => $suspensions,
            'pagination' => $pagination,
        ]);
    }

    private function viewDataSuspensions(&$suspensions = null)
    {
        if (empty($suspensions)) {
            return;
        }

        foreach ($suspensions as &$suspension) {
            $this->viewDataSuspension($suspension);
        }
    }

    private function viewDataSuspension(&$suspension = null)
    {
        if (empty($suspension)) {
            return;
        }

        $suspension["date"] = date("d.m.Y", ArrayHelper::getValue($suspension, "date", null));
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
            return $this->redirect(["/suspensions"]);
        } else {
            if ($action == "new") {
                $model = new Suspension();
                $url = Url::toRoute(['/suspension/save/']);
            } else if ($action == "edit") {
                $model = Suspension::find()->where(['id' => $id])->one();
                $model["date"] = date("Y-m-d", $model["date"]);
                $url = Url::toRoute(['/suspension/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Suspension::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['/suspensions']);
            }
        }

        return $this->render('suspension-add', [
            "action" => $action,
            "url" => $url,
            "model" => $model
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
            return $this->redirect(["/suspensions"]);
        } else {
            if ($action == "save") {
                $model = new Suspension();
            } else if ($action == "update") {
                $model = Suspension::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();

            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'SUSPENSION_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(['/suspensions']);
        } else {
            return $this->render('suspension-add', [
                'model' => $model,
            ]);
        }
    }
}