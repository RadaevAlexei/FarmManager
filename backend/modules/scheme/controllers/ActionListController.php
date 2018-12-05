<?php

namespace backend\modules\scheme\controllers;

use backend\modules\scheme\models\ActionListItem;
use common\helpers\DataHelper;
use Yii;
use backend\modules\scheme\models\ActionList;
use common\models\TypeList;
use backend\modules\scheme\models\search\ActionListSearch;
use backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\BadRequestHttpException;
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
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            $name = $request->post("name");
            $action_list_id = $request->post("action_list_id");

            /** @var ActionListItem $newItem */
            $newItem = new ActionListItem();
            $newItem->name = $name;
            $newItem->action_list_id = $action_list_id;
            $newItem->value = Inflector::slug($name);
            $newItem->sort = 100;

            if ($newItem->validate()) {
                $newItem->save();
            } else {
                $message = DataHelper::getArrayString($newItem->getErrors());
                throw new \Exception($message, 400);
            }

            $response->setStatusCode(200);
            $response->data["render"] = $this->renderAjax("new-item", [
                "model"        => $newItem,
                "actionListId" => $action_list_id,
            ]);
            $response->data["message"] = "Успешное добавление нового элемента списка";
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(400);
            $response->data["message"] = "Ошибка при добавлении элемента списка";
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }

    /**
     * @param $action_list_id
     * @param $item_id
     *
     * @return \yii\console\Response|Response
     */
    public function actionRemoveItem($action_list_id, $item_id)
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            ActionListItem::deleteAll([
                "action_list_id" => $action_list_id,
                "id"             => $item_id,
            ]);

            $response->setStatusCode(200);
            $response->data["message"] = "Элемент списка был успешно удалён";
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(404);
            $response->data["message"] = "Ошибка при удалении элемента списка";
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }
}