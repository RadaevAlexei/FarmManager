<?php

namespace backend\controllers;

use common\models\User;
use common\models\search\UserSearch;
use yii\data\ActiveDataProvider;

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