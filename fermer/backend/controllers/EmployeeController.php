<?php

namespace backend\controllers;

use common\models\Employee;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class EmployeeController extends Controller
{
    /**
     * Список сотрудников
     * @return string
     */
    public function actionIndex()
    {
        $query = Employee::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $employees = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with('function')
            ->asArray()
            ->all();

        $this->editDataEmployees($employees);

        return $this->render('employees', [
            'employees' => $employees,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Редактирование данных о сотруднике для вывода на страничку
     * @param $employees - список сотрудников
     */
    private function editDataEmployees(&$employees)
    {
        if (empty($employees)) {
            return;
        }

        foreach ($employees as &$employee) {
            $employee["fio"] = $this->concatIsNotEmptyValues([
                $employee["firstName"],
                $employee["lastName"],
                $employee["middleName"]
            ]);
            $employee["birthday"] = date("d.m.Y", $employee["birthday"]);
            $employee["gender"] = \Yii::t('app/back', 'EMPLOYEE_GENDER_' . $employee["gender"]);
        }

    }

    /**
     * Склеивание не пустых значений через разделитель $glue
     * @param null $arrValues
     * @param string $glue
     * @return null|string
     */
    private function concatIsNotEmptyValues($arrValues = null, $glue = " ")
    {
        if (empty($arrValues)) {
            return null;
        }

        $resultArray = [];

        foreach ($arrValues as $value) {
            if (!empty($value)) {
                $resultArray[] = $value;
            }
        }

        return implode($glue, $resultArray);
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
            return $this->redirect(["/employees"]);
        } else {
            if ($action == "new") {
                $model = new Employee();
                $url = Url::toRoute(['/employee/save/']);
            } else if ($action == "edit") {
                $model = Employee::find()->where(['id' => $id])->one();
                $model["birthday"] = date("Y-m-d", $model["birthday"]);
                $url = Url::toRoute(['/employee/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Employee::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['/employees']);
            }
        }

        return $this->render('employee-add', [
            "action" => $action,
            "url" => $url,
            "model" => $model
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
            return $this->redirect(["/employees"]);
        } else {
            if ($action == "save") {
                $model = new Employee();
            } else if ($action == "update") {
                $model = Employee::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'EMPLOYEE_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(['/employees']);
        } else {
            return $this->render('employee-add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionDetail($id = null)
    {
        $employee = Employee::find()->where(['id' => $id])->one();

        $employee["birthday"] = date("d.m.Y", $employee["birthday"]);
        $employee["gender"] = \Yii::t('app/back', 'EMPLOYEE_GENDER_' . $employee["gender"]);

        return $this->render('employee-detail', [
            'employee' => $employee
        ]);
    }
}