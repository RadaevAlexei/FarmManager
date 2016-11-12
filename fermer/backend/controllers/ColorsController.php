<?php

namespace backend\controllers;

use common\models\Color;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class ColorsController extends Controller
{
    /**
     * @return string
     */
    public function actionList()
    {
        $query = Color::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $colors = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('colors', [
            'colors' => $colors,
            'pagination' => $pagination,
        ]);
    }


    public function actionActions($action = null, $id = null)
    {
        $url = null;
        $model = null;

        if (empty($action)) {
            return $this->redirect(["colors/list"]);
        } else {
            if ($action == "new") {
                $model = new Color();
                $url = Url::toRoute(['/color/save/']);
            } else if ($action == "edit") {
                $model = Color::find()->where(['id' => $id])->one();
                $url = Url::toRoute(['/color/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Color::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['colors/list']);
            }
        }

        return $this->render('color-add', [
            "action" => $action,
            "url" => $url,
            "model" => $model
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSaveUpdate($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/colors"]);
        } else {
            if ($action == "save") {
                $model = new Color();
            } else if ($action == "update") {
                $model = Color::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'COLOR_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(["/colors"]);
        } else {
            return $this->render('color-add', [
                'model' => $model,
            ]);
        }
    }
}