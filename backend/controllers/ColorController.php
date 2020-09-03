<?php

namespace backend\controllers;

use common\helpers\ExcelHelper;
use Yii;
use common\models\Color;
use common\models\search\ColorSearch;
use yii\data\ActiveDataProvider;

/**
 * Масти коров
 *
 * Class ColorController
 * @package backend\controllers
 */
class ColorController extends BackendController
{
    /**
     * Список мастей
     *
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
     *
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        /** @var Color $model */
        $model = Color::findOne($id);

        return $this->render('detail',
            compact('model')
        );
    }

    /**
     * Страничка добавления новой масти
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Color([
            'scenario' => Color::SCENARIO_CREATE_EDIT
        ]);

        return $this->render('new',
            compact("model")
        );
    }

    /**
     * Создание масти
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var Color $model */
        $model = new Color([
            'scenario' => Color::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/color', 'COLOR_CREATE_SUCCESS'));
            return $this->redirect(["color/index"]);
        } else {
            $this->setFlash('error', Yii::t('app/color', 'COLOR_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка редактирования масти
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Color::findOne($id);

        return $this->render('edit',
            compact("model")
        );
    }

    /**
     * Обновление масти
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Color $model */
        $model = Color::findOne($id);

        $model->setScenario(Color::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/color', 'COLOR_EDIT_SUCCESS'));
            return $this->redirect(["color/index"]);
        } else {
            $this->setFlash('error', Yii::t('app/color', 'COLOR_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление масти
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Color $model */
        $model = Color::findOne($id);
        $model->delete();
        $this->setFlash('success', Yii::t('app/color', 'COLOR_DELETE_SUCCESS'));

        return $this->redirect(['color/index']);
    }
}
