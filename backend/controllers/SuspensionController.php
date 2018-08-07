<?php

namespace backend\controllers;

use common\models\Animal;
use common\models\Suspension;
use Yii;
use common\models\Cow;
use common\models\search\SuspensionSearch;
use yii\data\ActiveDataProvider;


/**
 * Class SuspensionController
 * @package backend\controllers
 */
class SuspensionController extends BackendController
{
    /**
     * Список перевесок
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var SuspensionSearch $searchModel */
        $searchModel = new SuspensionSearch([
            "scenario" => Cow::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Добавление новой перевески
     */
    public function actionCreate()
    {
        /** @var Suspension $model */
        $model = new Suspension([
            'scenario' => Suspension::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/suspension', 'SUSPENSION_CREATE_SUCCESS'));
            return $this->redirect(["suspension/index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/suspension', 'SUSPENSION_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка добавления новой перевески
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Suspension([
            'scenario' => Suspension::SCENARIO_CREATE_EDIT
        ]);

        $animals = Animal::getAllListAnimals();

        return $this->render('new',
            compact(
                "model",
                "animals"
            )
        );
    }

    /**
     * Страничка редактирования перевески
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Suspension::findOne($id);

        $animals = Animal::getAllListAnimals();

        return $this->render('edit',
            compact(
                "model",
                "animals"
            )
        );
    }

    /**
     * Обновление перевески
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Suspension $model */
        $model = Suspension::findOne($id);

        $model->setScenario(Suspension::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', Yii::t('app/suspension', 'SUSPENSION_EDIT_SUCCESS'));
            return $this->redirect(["suspension/index"]);
        } else {
            \Yii::$app->session->setFlash('error', Yii::t('app/suspension', 'SUSPENSION_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление перевески
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Suspension $model */
        $model = Suspension::findOne($id);
        $model->delete();
        \Yii::$app->session->setFlash('success', Yii::t('app/suspension', 'SUSPENSION_DELETE_SUCCESS'));

        return $this->redirect(['suspension/index']);
    }
}