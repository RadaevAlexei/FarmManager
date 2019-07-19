<?php

namespace backend\modules\reproduction\controllers;

use Yii;
use backend\modules\reproduction\models\search\SeedSupplierSearch;
use backend\modules\reproduction\models\SeedSupplier;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class SeedSupplierController
 * @package backend\modules\reproduction\controllers
 */
class SeedSupplierController extends BackendController
{
	/**
	 * Страничка со списком поставщиков семени
	 */
	public function actionIndex()
	{
		/** @var SeedSupplierSearch $searchModel */
		$searchModel = new SeedSupplierSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}

	/**
	 * Страничка добавления поставщика
	 *
	 * @return string
	 */
	public function actionNew()
	{
		/** @var SeedSupplier $model */
		$model = new SeedSupplier();

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
		/** @var SeedSupplier $model */
		$model = new SeedSupplier();

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			Yii::$app->session->setFlash('success', 'Успешное создание поставщика');

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', 'Ошибка при создании поставщика');

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования поставщика
	 *
	 * @return string
	 */
	public function actionEdit($id)
	{
	    /** @var SeedSupplier $model */
		$model = SeedSupplier::findOne($id);

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
		/** @var SeedSupplier $model */
		$model = SeedSupplier::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			Yii::$app->session->setFlash('success', 'Успешное обновление данных о поставщике');

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', 'Ошибка при обновлении данных о поставщике');

			return $this->render('edit',
				compact('model')
			);
		}
	}

	/**
	 * Удаление поставщика
	 *
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /** @var SeedSupplier $model */
            $model = SeedSupplier::findOne($id);
            $model->delete();

            Yii::$app->session->setFlash('success', 'Успешное удаление поставщика');
            $transaction->commit();
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении поставщика');
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
	}
}