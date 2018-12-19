<?php

namespace backend\modules\scheme\controllers;

use backend\modules\scheme\models\GroupsAction;
use backend\modules\scheme\models\links\SchemeDayGroupsActionLink;
use backend\modules\scheme\models\SchemeDay;
use common\helpers\DataHelper;
use Yii;
use backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;
use \backend\controllers\BackendController;
use backend\modules\scheme\models\search\SchemeSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class SchemeController
 */
class SchemeController extends BackendController
{
    /**
     * Страничка со списком схем лечения
     */
    public function actionIndex()
    {
        /** @var SchemeSearch $searchModel */
        $searchModel = new SchemeSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Страничка добавления новой схемы
     *
     * @return string
     */
    public function actionNew()
    {
        /** @var Scheme $model */
        $model = new Scheme();

        $diagnosisList = ArrayHelper::map(Diagnosis::getAllList(), "id", "name");

        return $this->render('new',
            compact('model', 'diagnosisList')
        );
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionCreate()
    {
        /** @var Scheme $model */
        $model = new Scheme();

        $isLoading = $model->load(Yii::$app->request->post());
        $model->created_by = Yii::$app->getUser()->getIdentity()->getId();

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/scheme', 'SCHEME_CREATE_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/scheme', 'SCHEME_CREATE_ERROR'));

            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * @param $id
     * Страничка редактирования схемы
     *
     * @return string
     */
    public function actionEdit($id)
    {
        /** @var Scheme $model */
        $model = Scheme::findOne($id);

        $diagnosisList = ArrayHelper::map(Diagnosis::getAllList(), "id", "name");

        $groupsActionList = ArrayHelper::map(GroupsAction::find()->all(), "id", "name");

        return $this->render('edit',
            compact('model', 'diagnosisList', 'groupsActionList')
        );
    }

    /**
     * @param $id
     * Обновление схемы
     *
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        /** @var Scheme $model */
        $model = Scheme::findOne($id);

        $isLoading = $model->load(Yii::$app->request->post());
        $model->created_by = Yii::$app->getUser()->getIdentity()->getId();

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/scheme', 'SCHEME_EDIT_SUCCESS'));

            return $this->redirect(["index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/scheme', 'SCHEME_EDIT_ERROR'));

            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * @param $id
     * Удаление схемы лечения
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        /** @var Scheme $model */
        $model = Scheme::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app/scheme', 'SCHEME_DELETE_SUCCESS'));

        return $this->redirect(['index']);
    }

    /**
     * @return \yii\console\Response|Response
     */
    public function actionAddNewGroupsAction()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            $post = Yii::$app->request->post();
            $scheme_day_id = ArrayHelper::getValue($post, "scheme_day_id");
            $groups_action_id = ArrayHelper::getValue($post, "groups_action_id");

            /** @var SchemeDayGroupsActionLink $newGroupsActionDayLink */
            $newGroupsActionDayLink = new SchemeDayGroupsActionLink();
            $newGroupsActionDayLink->scheme_day_id = $scheme_day_id;
            $newGroupsActionDayLink->groups_action_id = $groups_action_id;

            if ($newGroupsActionDayLink->validate()) {
                $newGroupsActionDayLink->save();
            } else {
                $message = DataHelper::getArrayString($newGroupsActionDayLink->getErrors());
                throw new \Exception($message, 400);
            }

            $response->setStatusCode(200);
            $groupsAction = GroupsAction::findOne($groups_action_id);
            $response->data["render"] = $this->renderAjax("new-groups-action", [
                "model" => $groupsAction
            ]);
            $response->data["message"] = "Успешное добавление группы действий в текущий день";
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(400);
            $response->data["message"] = "Ошибка при добавлении группы действий";
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }

    /**
     * @param $scheme_day_id
     * @param $groups_action_id
     *
     * @return \yii\console\Response|Response
     */
    public function actionRemoveGroupsAction($scheme_day_id, $groups_action_id)
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$request->isAjax) {
                throw new BadRequestHttpException();
            }

            SchemeDayGroupsActionLink::deleteAll([
                "scheme_day_id"    => $scheme_day_id,
                "groups_action_id" => $groups_action_id,
            ]);

            $response->setStatusCode(200);
            $response->data["message"] = "Группа была успешно удалёна";
            $transaction->commit();
        } catch (\Exception $exception) {
            $response->setStatusCode(404);
            $response->data["message"] = "Ошибка при удалении группы";
            $transaction->rollBack();
        }

        $response->format = Response::FORMAT_JSON;

        return $response;
    }
}