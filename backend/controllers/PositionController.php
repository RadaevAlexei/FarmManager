<?php

namespace backend\controllers;

use Yii;
use common\models\Position;
use common\models\search\PositionSearch;
use yii\data\ActiveDataProvider;

/**
 * Должности
 *
 * Class PositionController
 * @package backend\controllers
 */
class PositionController extends BackendController
{
    /**
     * Список должностей
     * @return string
     */
    public function actionIndex()
    {
        /** @var PositionSearch $searchModel */
        $searchModel = new PositionSearch([
            "scenario" => Position::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);
    }

    /**
     * Детальная карточка должности
     *
     * @param $id
     * @return string
     */
    public function actionDetail($id)
    {
        /** @var Position $model */
        $model = Position::findOne($id);

        return $this->render('detail',
            compact('model')
        );
    }

    /**
     * Страничка добавления новой должности
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Position([
            'scenario' => Position::SCENARIO_CREATE_EDIT
        ]);

        return $this->render('new',
            compact("model")
        );
    }

    /**
     * Создание должности
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var Position $model */
        $model = new Position([
            'scenario' => Position::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/position', 'POSITION_CREATE_SUCCESS'));
            return $this->redirect(["position/index"]);
        } else {
            $this->setFlash('error', Yii::t('app/position', 'POSITION_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка редактирования должности
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Position::findOne($id);

        return $this->render('edit',
            compact("model")
        );
    }

    /**
     * Обновление должности
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Position $model */
        $model = Position::findOne($id);

        $model->setScenario(Position::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $this->setFlash('success', Yii::t('app/position', 'POSITION_EDIT_SUCCESS'));
            return $this->redirect(["position/index"]);
        } else {
            $this->setFlash('error', Yii::t('app/position', 'POSITION_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление должности
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Position $model */
        $model = Position::findOne($id);
        $model->delete();
        $this->setFlash('success', Yii::t('app/position', 'POSITION_DELETE_SUCCESS'));

        return $this->redirect(['position/index']);
    }
}
