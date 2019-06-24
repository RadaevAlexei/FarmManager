<?php

namespace backend\modules\pharmacy\controllers;

use Yii;
use backend\modules\pharmacy\models\search\StorageSearch;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class StorageController
 * @package backend\modules\pharmacy\controllers
 */
class StorageController extends BackendController
{
	/**
	 * Страничка со списком препаратов и месте их хранения
	 */
	public function actionIndex()
	{
		/** @var StorageSearch $searchModel */
		$searchModel = new StorageSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}
}