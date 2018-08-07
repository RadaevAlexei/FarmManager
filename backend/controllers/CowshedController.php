<?php

namespace backend\controllers;

use common\models\Cowshed;
use common\models\search\CowshedSearch;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Коровники
 *
 * Class CowshedController
 * @package backend\controllers
 */
class CowshedController extends BackendController
{
    /**
     * Список коровников
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var CowshedSearch $searchModel */
        $searchModel = new CowshedSearch([
            "scenario" => Cowshed::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка коровника
     *
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        /** @var Cowshed $model */
        $model = Cowshed::findOne($id);

        return $this->render('detail',
            compact('model')
        );
    }

    /**
     * Страничка добавления нового коровника
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Cowshed([
            'scenario' => Cowshed::SCENARIO_CREATE_EDIT
        ]);

        return $this->render('new',
            compact("model")
        );
    }

    /**
     * Создание коровника
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var Cowshed $model */
        $model = new Cowshed([
            'scenario' => Cowshed::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', Yii::t('app/cowshed', 'COWSHED_CREATE_SUCCESS'));
            return $this->redirect(["cowshed/index"]);
        } else {
            \Yii::$app->session->setFlash('error', Yii::t('app/cowshed', 'COWSHED_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка редактирования коровника
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Cowshed::findOne($id);

        return $this->render('edit',
            compact("model")
        );
    }

    /**
     * Обновление коровника
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Cowshed $model */
        $model = Cowshed::findOne($id);

        $model->setScenario(Cowshed::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', Yii::t('app/cowshed', 'COWSHED_EDIT_SUCCESS'));
            return $this->redirect(["cowshed/index"]);
        } else {
            \Yii::$app->session->setFlash('error', Yii::t('app/cowshed', 'COWSHED_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление коровника
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Cowshed $model */
        $model = Cowshed::findOne($id);
        $model->delete();
        \Yii::$app->session->setFlash('success', Yii::t('app/cowshed', 'COWSHED_DELETE_SUCCESS'));

        return $this->redirect(['cowshed/index']);
    }
}