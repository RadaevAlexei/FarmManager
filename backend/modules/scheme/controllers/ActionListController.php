<?php

namespace backend\modules\scheme\controllers;

use backend\modules\scheme\models\ActionListItem;
use Yii;
use backend\modules\scheme\models\ActionList;
use common\models\TypeList;
use backend\modules\scheme\models\search\ActionListSearch;
use backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\Response;

/**
 * Class ActionListController
 * @package backend\modules\scheme\controllers
 */
class ActionListController extends BackendController
{
    /**
     * Страничка со списком списков
     */
    public function actionIndex()
    {
        /** @var ActionListSearch $searchModel */
        $searchModel = new ActionListSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Страничка добавления нового списка
     *
     * @return string
     */
    public function actionNew()
    {
        /** @var ActionList $model */
        $model = new ActionList();

        $typeList = TypeList::getList();

        return $this->render('new',
            compact('model', 'typeList')
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var ActionList $model */
        $model = new ActionList();

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/action-list', 'ACTION_LIST_CREATE_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/action-list', 'ACTION_LIST_CREATE_ERROR'));

            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка редактирования списка
     *
     * @param $id
     *
     * @return string
     */
    public function actionEdit($id)
    {
        /** @var ActionList $model */
        $model = ActionList::findOne($id);

        $typeList = TypeList::getList();

        return $this->render('edit',
            compact('model', 'typeList')
        );
    }

    /**
     * @param $id
     * Обновление списка
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var ActionList $model */
        $model = ActionList::findOne($id);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/action-list', 'ACTION_LIST_EDIT_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/action-list', 'ACTION_LIST_EDIT_ERROR'));

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
        /** @var ActionList $model */
        $model = ActionList::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app/action-list', 'ACTION_LIST_DELETE_SUCCESS'));

        return $this->redirect(['index']);
    }

    public function actionAddNewItem()
    {
        $post = Yii::$app->request->post();

        $model = new ActionListItem();
        $model->name = $post["name"];
        $model->load($post);
echo "<pre>"; print_r($model); echo "</pre>"; die("Debug");
        if ($model->validate()) {
            echo "<pre>"; print_r($model); echo "</pre>"; die("Debug");
            return $this->renderAjax('templates/action-list-items', [

            ]);
        }
    }

    /**
     * @param $action_list_id
     * @param $item_id
     *
     * @return Response
     */
    public function actionRemoveItem($action_list_id, $item_id)
    {
        try {
            ActionListItem::find()
                ->where([
                    "action_list_id" => $action_list_id,
                    "id"             => $item_id,
                ])
                ->one()
                ->delete();

            Yii::$app->session->setFlash('success', Yii::t('app/action-list', 'ACTION_LIST_DELETE_ITEM_SUCCESS'));
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('success', Yii::t('app/action-list', 'ACTION_LIST_DELETE_ITEM_ERROR'));
        }

        return $this->redirect(['edit', 'id' => $action_list_id]);
    }
}