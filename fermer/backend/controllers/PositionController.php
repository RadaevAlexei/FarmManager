<?php

namespace backend\controllers;

use common\models\Position;
use common\models\search\PositionSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class PositionController
 * @package backend\controllers
 */
class PositionController extends BackendController
{
    /**
     * Список должностей
     * @return string
     */
    public function actionIndex()
    {
        /** @var PositionSearch $searchModel */
        $searchModel = new PositionSearch([
            "scenario" => Position::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка должности
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        /** @var Position $position */
        $position = Position::findOne($id);

        return $this->render('detail', [
            "position" => $position
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