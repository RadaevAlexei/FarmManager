<?php

namespace backend\modules\scheme\controllers;

use Yii;
use backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;
use \backend\controllers\BackendController;
use backend\modules\scheme\models\search\SchemeSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class SchemeController
 */
class SchemeController extends BackendController
{
	/**
	 * Страничка со списком схем лечения
	 */
	public function actionIndex()
	{
		/** @var SchemeSearch $searchModel */
		$searchModel = new SchemeSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}

	/**
	 * Страничка добавления новой схемы
	 *
	 * @return string
	 */
	public function actionNew()
	{
		/** @var Scheme $model */
		$model = new Scheme();

		$diagnosisList = ArrayHelper::map(Diagnosis::getAllList(), "id", "name");

		return $this->render('new',
			compact('model', 'diagnosisList')
		);
	}

	/**
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionCreate()
	{
		/** @var Scheme $model */
		$model = new Scheme();

		$isLoading = $model->load(Yii::$app->request->post());
		$model->created_by = Yii::$app->getUser()->getIdentity()->getId();

		if ($isLoading && $model->validate()) {
			$model->save();
			Yii::$app->session->setFlash('success', Yii::t('app/scheme', 'SCHEME_CREATE_SUCCESS'));

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', Yii::t('app/scheme', 'SCHEME_CREATE_ERROR'));

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования схемы
	 * @return string
	 */
	public function actionEdit($id)
	{
		$model = Scheme::findOne($id);

		$diagnosisList = ArrayHelper::map(Diagnosis::getAllList(), "id", "name");

		return $this->render('edit',
			compact('model', 'diagnosisList')
		);
	}

	/**
	 * @param $id
	 * Обновление схемы
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionUpdate($id)
	{
		/** @var Scheme $model */
		$model = Scheme::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());
		$model->created_by = Yii::$app->getUser()->getIdentity()->getId();

		if ($isLoading && $model->validate()) {
			$model->save();
			Yii::$app->session->setFlash('success', Yii::t('app/scheme', 'SCHEME_EDIT_SUCCESS'));

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', Yii::t('app/scheme', 'SCHEME_EDIT_ERROR'));

			return $this->render('edit',
				compact('model')
			);
		}
	}

	/**
	 * @param $id
	 * Удаление схемы лечения
	 *
	 * @return \yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		/** @var Scheme $model */
		$model = Scheme::findOne($id);
		$model->delete();
		Yii::$app->session->setFlash('success', Yii::t('app/scheme', 'SCHEME_DELETE_SUCCESS'));

		return $this->redirect(['index']);
	}
}