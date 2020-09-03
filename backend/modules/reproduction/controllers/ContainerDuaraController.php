<?php

namespace backend\modules\reproduction\controllers;

use backend\modules\reproduction\models\ContainerDuara;
use backend\modules\reproduction\models\search\ContainerDuaraSearch;
use Yii;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class ContainerDuaraController
 * @package backend\modules\pharmacy\controllers
 */
class ContainerDuaraController extends BackendController
{
	/**
	 * Страничка со списком складов
	 */
	public function actionIndex()
	{
		/** @var ContainerDuaraSearch $searchModel */
		$searchModel = new ContainerDuaraSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}

	/**
	 * Страничка добавления сосуда
	 *
	 * @return string
	 */
	public function actionNew()
	{
		/** @var ContainerDuara $model */
		$model = new ContainerDuara();

		return $this->render('new',
			compact('model')
		);
	}

	/**
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionCreate()
	{
		/** @var ContainerDuara $model */
		$model = new ContainerDuara();

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			$this->setFlash('success', 'Успешное создание сосуда');

			return $this->redirect(["index"]);
		} else {
			$this->setFlash('error', 'Ошибка при создании сосуда');

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования сосуда
	 *
	 * @return string
	 */
	public function actionEdit($id)
	{
		$model = ContainerDuara::findOne($id);

		return $this->render('edit',
			compact('model')
		);
	}

	/**
	 * @param $id
	 * Обновление сосуда
	 *
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionUpdate($id)
	{
		/** @var ContainerDuara $model */
		$model = ContainerDuara::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			$this->setFlash('success', 'Успешное обновление данных о сосуде');

			return $this->redirect(["index"]);
		} else {
			$this->setFlash('error', 'Ошибка при обновлении данных о сосуде');

			return $this->render('edit',
				compact('model')
			);
		}
	}

    /**
     * Удаление сосуда
     *
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
	public function actionDelete($id)
	{
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /** @var ContainerDuara $model */
            $model = ContainerDuara::findOne($id);
            $model->delete();
            $this->setFlash('success', 'Успешное удаление сосуда');
            $transaction->commit();
        } catch (\Exception $exception) {
            $this->setFlash('error', 'Ошибка при удалении семени');
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
	}
}
