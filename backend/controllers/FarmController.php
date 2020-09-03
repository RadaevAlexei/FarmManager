<?php

namespace backend\controllers;

use common\models\Farm;
use common\models\search\FarmSearch;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Фермы
 *
 * Class FarmController
 * @package backend\controllers
 */
class FarmController extends BackendController
{
    /**
     * Список ферм
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var FarmSearch $searchModel */
        $searchModel = new FarmSearch([
            "scenario" => Farm::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка фермы
     *
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        /** @var Farm $model */
        $model = Farm::findOne($id);

        return $this->render('detail',
            compact('model')
        );
    }

    /**
     * Страничка добавления новой фермы
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Farm([
            'scenario' => Farm::SCENARIO_CREATE_EDIT
        ]);

        return $this->render('new',
            compact("model")
        );
    }

    /**
     * Создание фермы
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var Farm $model */
        $model = new Farm([
            'scenario' => Farm::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/farm', 'FARM_CREATE_SUCCESS'));
            return $this->redirect(["farm/index"]);
        } else {
            $this->setFlash('error', Yii::t('app/farm', 'FARM_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка редактирования фермы
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Farm::findOne($id);

        return $this->render('edit',
            compact("model")
        );
    }

    /**
     * Обновление фермы
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Farm $model */
        $model = Farm::findOne($id);

        $model->setScenario(Farm::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/farm', 'FARM_EDIT_SUCCESS'));
            return $this->redirect(["farm/index"]);
        } else {
            $this->setFlash('error', Yii::t('app/farm', 'FARM_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление фермы
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Farm $model */
        $model = Farm::findOne($id);
        $model->delete();
        $this->setFlash('success', Yii::t('app/farm', 'FARM_DELETE_SUCCESS'));

        return $this->redirect(['farm/index']);
    }
}
