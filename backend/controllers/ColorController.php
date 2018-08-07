<?php

namespace backend\controllers;

use common\models\Color;
use common\models\search\ColorSearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ColorController
 * @package backend\controllers
 */
class ColorController extends BackendController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        /** @var ColorSearch $searchModel */
        $searchModel = new ColorSearch([
            "scenario" => Color::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка масти
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        /** @var Color $color */
        $color = Color::findOne($id);

        return $this->render('detail', [
            "color" => $color
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
            "url"    => $url,
            "model"  => $model
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