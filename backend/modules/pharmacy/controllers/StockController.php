<?php

namespace backend\modules\pharmacy\controllers;

use Yii;
use backend\modules\pharmacy\models\search\StockSearch;
use backend\modules\pharmacy\models\Stock;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class StockController
 * @package backend\modules\pharmacy\controllers
 */
class StockController extends BackendController
{
	/**
	 * Страничка со списком складов
	 */
	public function actionIndex()
	{
		/** @var StockSearch $searchModel */
		$searchModel = new StockSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}

	/**
	 * Страничка добавления склада
	 *
	 * @return string
	 */
	public function actionNew()
	{
		/** @var Stock $model */
		$model = new Stock();

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
		/** @var Stock $model */
		$model = new Stock();

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			Yii::$app->session->setFlash('success', 'Успешное создание склада');

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', 'Ошибка при создании склада');

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования склада
	 *
	 * @return string
	 */
	public function actionEdit($id)
	{
		$model = Stock::findOne($id);

		return $this->render('edit',
			compact('model')
		);
	}

	/**
	 * @param $id
	 * Обновление склада
	 *
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionUpdate($id)
	{
		/** @var Stock $model */
		$model = Stock::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			Yii::$app->session->setFlash('success', 'Успешное обновление данных о складе');

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', 'Ошибка при обновлении данных о складе');

			return $this->render('edit',
				compact('model')
			);
		}
	}

	/**
	 * Удаление склада
	 *
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		/** @var Stock $model */
		$model = Stock::findOne($id);
		$model->delete();
		Yii::$app->session->setFlash('success', 'Успешное удаление склада');

		return $this->redirect(['index']);
	}
}