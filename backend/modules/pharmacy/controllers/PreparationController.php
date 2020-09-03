<?php

namespace backend\modules\pharmacy\controllers;

use backend\modules\pharmacy\models\Preparation;
use backend\modules\pharmacy\models\search\PreparationSearch;
use common\models\Packing;
use Yii;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class SchemeController
 */
class PreparationController extends BackendController
{
	/**
	 * Страничка со списком препаратов
	 */
	public function actionIndex()
	{
		/** @var PreparationSearch $searchModel */
		$searchModel = new PreparationSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}

	/**
	 * Страничка добавления препарата
	 *
	 * @return string
	 */
	public function actionNew()
	{
		/** @var Preparation $model */
		$model = new Preparation();

		$packingList = Packing::getList();

		return $this->render('new',
			compact('model', 'packingList')
		);
	}

	/**
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionCreate()
	{
		/** @var Preparation $model */
		$model = new Preparation();

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			$this->setFlash('success', Yii::t('app/preparation', 'PREPARATION_CREATE_SUCCESS'));

			return $this->redirect(["index"]);
		} else {
			$this->setFlash('error', Yii::t('app/preparation', 'PREPARATION_CREATE_ERROR'));

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования данных о препарате
	 *
	 * @return string
	 */
	public function actionEdit($id)
	{
		$model = Preparation::findOne($id);

		$packingList = Packing::getList();

		return $this->render('edit',
			compact('model', 'packingList')
		);
	}

	/**
	 * @param $id
	 * Обновление схемы
	 *
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionUpdate($id)
	{
		/** @var Preparation $model */
		$model = Preparation::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			$this->setFlash('success', Yii::t('app/preparation', 'PREPARATION_EDIT_SUCCESS'));

			return $this->redirect(["index"]);
		} else {
			$this->setFlash('error', Yii::t('app/preparation', 'PREPARATION_EDIT_ERROR'));

			return $this->render('edit',
				compact('model')
			);
		}
	}

	/**
	 * Удаление препарата
	 *
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		/** @var Preparation $model */
		$model = Preparation::findOne($id);
		$model->delete();
		$this->setFlash('success', Yii::t('app/preparation', 'PREPARATION_DELETE_SUCCESS'));

		return $this->redirect(['index']);
	}
}
