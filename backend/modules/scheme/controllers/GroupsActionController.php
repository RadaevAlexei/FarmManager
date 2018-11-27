<?php

namespace backend\modules\scheme\controllers;

use Yii;
use backend\modules\scheme\models\GroupsAction;
use backend\modules\scheme\models\search\GroupsActionSearch;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class GroupsActionController
 * @package backend\modules\scheme\controllers
 */
class GroupsActionController extends BackendController
{
    /**
     * Страничка со списком групп действий
     */
    public function actionIndex()
    {
        /** @var GroupsActionSearch $searchModel */
        $searchModel = new GroupsActionSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Страничка добавления новой группы
     *
     * @return string
     */
    public function actionNew()
    {
        /** @var GroupsAction $model */
        $model = new GroupsAction();

        return $this->render('new',
            compact('model')
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var GroupsAction $model */
        $model = new GroupsAction();

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/groups-action', 'GROUPS_ACTION_CREATE_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/groups-action', 'GROUPS_ACTION_CREATE_ERROR'));

            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * @param $id
     * Страничка редактирования группы
     * @return string
     */
    public function actionEdit($id)
    {
        /** @var GroupsAction $model */
        $model = GroupsAction::findOne($id);

        return $this->render('edit',
            compact('model')
        );
    }

    /**
     * @param $id
     * Обновление группы
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var GroupsAction $model */
        $model = GroupsAction::findOne($id);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/groups-action', 'GROUPS_ACTION_EDIT_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/groups-action', 'GROUPS_ACTION_EDIT_ERROR'));

            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * @param $id
     * Удаление группы
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var GroupsAction $model */
        $model = GroupsAction::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app/groups-action', 'GROUPS_ACTION_DELETE_SUCCESS'));

        return $this->redirect(['index']);
    }
}