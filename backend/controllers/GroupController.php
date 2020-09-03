<?php

namespace backend\controllers;

use common\models\Employee;
use common\models\Group;
use common\models\Groups;
use common\models\search\GroupSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class GroupController
 * @package backend\controllers
 */
class GroupController extends BackendController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        /** @var GroupSearch $searchModel */
        $searchModel = new GroupSearch([
            "scenario" => Group::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка группы
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        /** @var Group $group */
        $group = Group::findOne($id);

        return $this->render('group-detail', [
            "group" => $group
        ]);
    }

    /**
     * @param null $action
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionActions($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/groups"]);
        } else {
            if ($action == "new") {
                $model = new Groups();
                $url = Url::toRoute(['/group/save/']);
            } else if ($action == "edit") {
                $model = Groups::find()->where(['id' => $id])->one();
                $url = Url::toRoute(['/group/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Groups::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['/groups']);
            }
        }

        $employees = Employee::find()->select(['firstName', 'lastName', 'middleName', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column();

        return $this->render('group-add', [
            "action" => $action,
            "url" => $url,
            "model" => $model,
            "employees" => $employees
        ]);
    }

    /**
     * @param null $action
     * @param null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveUpdate($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/groups"]);
        } else {
            if ($action == "save") {
                $model = new Groups();
            } else if ($action == "update") {
                $model = Groups::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', \Yii::t('app/back', 'GROUP_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(['/groups']);
        } else {
            return $this->render('group-add', [
                'model' => $model,
            ]);
        }
    }

}
