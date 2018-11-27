<?php

namespace backend\modules\scheme\controllers;

use backend\modules\scheme\models\Action;
use backend\modules\scheme\models\search\ActionSearch;
use common\models\TypeField;
use Yii;
use backend\modules\scheme\models\GroupsAction;
use backend\modules\scheme\models\search\GroupsActionSearch;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class ActionController
 * @package backend\modules\scheme\controllers
 */
class ActionController extends BackendController
{
    /**
     * Страничка со списком групп действий
     */
    public function actionIndex()
    {
        /** @var ActionSearch $searchModel */
        $searchModel = new ActionSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Страничка добавления нового действия
     *
     * @return string
     */
    public function actionNew()
    {
        /** @var Action $model */
        $model = new Action();

        $typeFieldList = TypeField::getList();

        return $this->render('new',
            compact('model', 'typeFieldList')
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var Action $model */
        $model = new Action();

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/action', 'ACTION_CREATE_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/action', 'ACTION_CREATE_ERROR'));

            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * @param $id
     * Страничка редактирования действия
     *
     * @return string
     */
    public function actionEdit($id)
    {
        /** @var Action $model */
        $model = Action::findOne($id);

        $typeFieldList = TypeField::getList();

        return $this->render('edit',
            compact('model', 'typeFieldList')
        );
    }

    /**
     * @param $id
     * Обновление действия
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Action $model */
        $model = Action::findOne($id);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/action', 'ACTION_EDIT_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/action', 'ACTION_EDIT_ERROR'));

            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * @param $id
     * Удаление действия
     *
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Action $model */
        $model = Action::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app/action', 'ACTION_DELETE_SUCCESS'));

        return $this->redirect(['index']);
    }
}