<?php

namespace backend\controllers;

use common\models\Employee;
use common\models\User;
use common\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @package backend\controllers
 */
class UserController extends BackendController
{
    /**
     * Список сотрудников
     * @return string
     */
    public function actionIndex()
    {
        /** @var UserSearch $searchModel */
        $searchModel = new UserSearch([
            "scenario" => User::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка сотрудника
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        /** @var User $user */
        $user = User::findOne($id);

        return $this->render('detail', [
            "user" => $user
        ]);
    }

}