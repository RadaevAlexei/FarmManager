<?php

namespace backend\controllers;

use common\models\Functions;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class FunctionsController extends Controller
{
    /**
     * @return string
     */
    public function actionList()
    {
        $query = Functions::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $functions = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('functions', [
            'functions' => $functions,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Событие при:
     * 1) Открытии странички на создание новой должности
     * 2) Открытии странички на редактирование должности с id = $id
     * 3) Удалении должности с id = $id
     * @param null $action - Действие
     * @param null $id - уникальный идентификатор должности
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionActions($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["functions/list"]);
        } else {
            if ($action == "new") {
                $model = new Functions();
                $url = Url::toRoute(['/function/save/']);
            } else if ($action == "edit") {
                $model = Functions::find()->where(['id' => $id])->one();
                $url = Url::toRoute(['/function/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Functions::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['functions-list']);
            }
        }

        return $this->render('function-add', [
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
            return $this->redirect(["/functions"]);
        } else {
            if ($action == "save") {
                $model = new Functions();
            } else if ($action == "update") {
                $model = Functions::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'FUNCTION_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(["/functions"]);
        } else {
            return $this->render('function-add', [
                'model' => $model,
            ]);
        }
    }
}