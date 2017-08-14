<?php

namespace backend\controllers;

use common\models\Employee;
use common\models\Groups;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
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
    public function actionList()
    {
        $query = Groups::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $groups = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('groups', [
            'groups' => $groups,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionDetail($id = null)
    {
        $group = Groups::find()->where(['id' => $id])->one();

        return $this->render('group-detail', [
            'group' => $group
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
            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'GROUP_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(['/groups']);
        } else {
            return $this->render('group-add', [
                'model' => $model,
            ]);
        }
    }

}