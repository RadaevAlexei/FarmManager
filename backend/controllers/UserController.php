<?php

namespace backend\controllers;

use Yii;
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
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var UserSearch $searchModel */
        $searchModel = new UserSearch([
            "scenario" => User::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка сотрудника
     *
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        /** @var User $model */
        $model = User::findOne($id);

        return $this->render('detail',
            compact('model')
        );
    }

    /**
     * Страничка добавления нового сотрудника
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new User([
            'scenario' => User::SCENARIO_CREATE_EDIT
        ]);

        return $this->render('new',
            compact("model")
        );
    }

    /**
     * Создание сотрудника
     *
     * @return string|Yii\web\Response
     */
    public function actionCreate()
    {
        /** @var User $model */
        $model = new User([
            'scenario' => User::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(Yii::$app->request->post());

        // TODO:: Сделать генерацию пароля
        $model->setPassword('123123123');
        $model->generateAuthKey();

        if ($isLoading && $model->validate()) {
            try {
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('app/user', 'USER_CREATE_SUCCESS'));
            } catch (\yii\db\Exception $exception) {
                Yii::$app->session->setFlash('error', Yii::t('app/user', 'USER_CREATE_ERROR'));
            }
            return $this->redirect(["user/index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'USER_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка редактирования сотрудника
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = User::findOne($id);

        return $this->render('edit',
            compact("model")
        );
    }

    /**
     * Обновление данных сотрудника
     *
     * @return string|Yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var User $model */
        $model = User::findOne($id);

        $model->setScenario(User::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'USER_EDIT_SUCCESS'));
            return $this->redirect(["user/index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'USER_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление сотрудника
     *
     * @return string|Yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var User $model */
        $model = User::findOne($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app/user', 'USER_DELETE_SUCCESS'));

        return $this->redirect(['user/index']);
    }

}