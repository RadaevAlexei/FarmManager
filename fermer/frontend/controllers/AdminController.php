<?php

namespace frontend\controllers;


use common\models\Calf;
use common\models\Employee;
use common\models\Functions;
use common\models\Groups;
use yii\data\Pagination;
use yii\web\Controller;

/**
 * Class AdminController
 * @package frontend\controllers
 */
class AdminController extends Controller
{
    /**
     * @return string
     */
    public function actionEmployeesList()
    {
        $query = Employee::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $employees = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('employees', [
            'employees' => $employees,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionEmployeeDetail($id = null)
    {
        $employee = Employee::find()->where(['id' => $id])->one();

        return $this->render('employee-detail', [
            'employee' => $employee
        ]);
    }

    /**
     * @return string
     */
    public function actionEmployeeAdd()
    {
        $model = new Employee();

        return $this->render('employee-add', [
            "model" => $model
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionEmployeeSave()
    {
        $model = new Employee();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', 'Успешное сохранение сотрудника');
            return $this->refresh();
        } else {
            return $this->render('employee-add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param null $id
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionEmployeeDelete($id = null)
    {
        if (!empty($id)) {
            Employee::find()->where(['id' => $id])->one()->delete();
            return $this->redirect(['employees-list']);
        }
        return $this->refresh();
    }

    /**
     * @return string
     */
    public function actionGroupsList()
    {
        $query = Groups::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $groups = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
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
    public function actionGroupDetail($id = null)
    {
        $group = Groups::find()->where(['id' => $id])->one();

        return $this->render('group-detail', [
            'group' => $group
        ]);
    }

    /**
     * @return string
     */
    public function actionGroupAdd()
    {
        return $this->render('group-add', [
            "model" => ""
        ]);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionGroupSave()
    {
        $model = new Groups();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', 'Успешное сохранение группы');
            return $this->refresh();
        } else {
            return $this->render('group-add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param null $id
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionGroupDelete($id = null)
    {
        if (!empty($id)) {
            Groups::find()->where(['id' => $id])->one()->delete();
            return $this->redirect(['groups-list']);
        }
        return $this->refresh();
    }

    /**
     * @return string
     */
    public function actionFunctionsList()
    {
        $query = Functions::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $functions = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('functions', [
            'functions' => $functions,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @return string
     */
    public function actionFunctionAdd()
    {
        $model = new Functions();

        return $this->render('function-add', [
            "model" => $model
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionFunctionSave()
    {
        $model = new Functions();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', 'Успешное сохранение должности');
            return $this->refresh();
        } else {
            return $this->render('function-add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param null $id
     * @return \yii\web\Response
     */
    public function actionFunctionDelete($id = null)
    {
        if (!empty($id)) {
            Functions::find()->where(['id' => $id])->one()->delete();
            return $this->redirect(['functions-list']);
        }
        return $this->refresh();
    }

    /**
     * @return string
     */
    public function actionCalfAdd()
    {
        return $this->render('calf-add', [
            "model" => ""
        ]);
    }

}