<?php

namespace backend\modules\scheme\controllers;

use Yii;
use backend\modules\scheme\models\Action;
use backend\modules\scheme\models\links\GroupsActionLink;
use common\helpers\DataHelper;
use backend\modules\scheme\models\GroupsAction;
use backend\modules\scheme\models\search\GroupsActionSearch;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

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
            $this->setFlash('success', Yii::t('app/groups-action', 'GROUPS_ACTION_CREATE_SUCCESS'));

            return $this->redirect(["edit", 'id' => $model->id]);
        } else {
            $this->setFlash('error', Yii::t('app/groups-action', 'GROUPS_ACTION_CREATE_ERROR'));

            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * @param $id
     * Страничка редактирования группы
     *
     * @return string
     */
    public function actionEdit($id)
    {
        /** @var GroupsAction $model */
        $model = GroupsAction::findOne($id);

        $actionList = ArrayHelper::map(Action::find()->all(), "id", "name");

        return $this->render('edit',
            compact('model', 'actionList')
        );
    }

    /**
     * @param $id
     * Обновление группы
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var GroupsAction $model */
        $model = GroupsAction::findOne($id);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/groups-action', 'GROUPS_ACTION_EDIT_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            $this->setFlash('error', Yii::t('app/groups-action', 'GROUPS_ACTION_EDIT_ERROR'));

            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * @param $id
     * Удаление группы действий
     * @return \yii\console\Response|Response
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            $model = GroupsAction::findOne($id);
            $model->delete();

            GroupsActionLink::deleteAll([
                "groups_action_id" => $id,
            ]);

            $response->setStatusCode(200);
            $response->data["message"] = Yii::t('app/groups-action', 'GROUPS_ACTION_DELETE_SUCCESS');
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(404);
            $response->data["message"] = Yii::t('app/groups-action', 'GROUPS_ACTION_DELETE_ERROR');
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionAddNewAction()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            $groupsActionId = $request->post("groups_action_id");
            $actionId = $request->post("action_id");

            /** @var GroupsActionLink $newGroupsActionLink */
            $newGroupsActionLink = new GroupsActionLink();
            $newGroupsActionLink->groups_action_id = $groupsActionId;
            $newGroupsActionLink->action_id = $actionId;

            if ($newGroupsActionLink->validate()) {
                $newGroupsActionLink->save();
            } else {
                $message = DataHelper::getArrayString($newGroupsActionLink->getErrors());
                throw new \Exception($message, 400);
            }

            $response->setStatusCode(200);
            $actionLink = Action::findOne($actionId);
            $response->data["render"] = $this->renderAjax("new-action", [
                "model"          => $actionLink,
                "groupsActionId" => $groupsActionId,
            ]);
            $response->data["message"] = "Успешное добавление действия в группу";
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(400);
            $response->data["message"] = "Ошибка при добавлении действия в группу";
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }

    /**
     * @param $groups_action_id
     * @param $action_id
     *
     * @return \yii\console\Response|Response
     */
    public function actionRemoveAction($groups_action_id, $action_id)
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            GroupsActionLink::deleteAll([
                "groups_action_id" => $groups_action_id,
                "action_id"        => $action_id,
            ]);

            $response->setStatusCode(200);
            $response->data["message"] = "Действие было успешно удалёно из группы";
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(404);
            $response->data["message"] = "Ошибка при удалении действия из группы";
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }
}
