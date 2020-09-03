<?php

namespace backend\controllers;

use Yii;
use common\models\AnimalGroup;
use common\models\Employee;
use common\models\Group;
use common\models\Groups;
use common\models\search\AnimalGroupSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class AnimalGroupController
 * @package backend\controllers
 */
class AnimalGroupController extends BackendController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        /** @var AnimalGroupSearch $searchModel */
        $searchModel = new AnimalGroupSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionNew()
    {
        /** @var AnimalGroup $model */
        $model = new AnimalGroup();

        return $this->render('new',
            compact('model')
        );
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionCreate()
    {
        /** @var AnimalGroup $model */
        $model = new AnimalGroup();

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', 'Успешное создание группы');

            return $this->redirect(["index"]);
        } else {
            $this->setFlash('error', 'Ошибка при создании группы');

            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionEdit($id)
    {
        $model = AnimalGroup::findOne($id);

        return $this->render('edit',
            compact('model')
        );
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var AnimalGroup $model */
        $model = AnimalGroup::findOne($id);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', 'Успешное обновление группы');

            return $this->redirect(["index"]);
        } else {
            $this->setFlash('error', 'Ошибка при обновлении группы');

            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        /** @var AnimalGroup $model */
        $model = AnimalGroup::findOne($id);
        $model->delete();
        $this->setFlash('success', 'Успешное удаление группы');

        return $this->redirect(['index']);
    }

}
