<?php

namespace backend\modules\reproduction\controllers;

use backend\modules\reproduction\models\SeedSupplier;
use Yii;
use backend\modules\reproduction\models\search\SeedBullSearch;
use backend\modules\reproduction\models\SeedBull;
use common\models\Breed;
use common\models\Color;
use common\models\ContractorSeed;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class SeedBullController
 * @package backend\modules\reproduction\controllers
 */
class SeedBullController extends BackendController
{
	/**
	 * Страничка со списком семени
	 */
	public function actionIndex()
	{
		/** @var SeedBullSearch $searchModel */
		$searchModel = new SeedBullSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index',
			compact('searchModel', 'dataProvider')
		);
	}

	/**
	 * Страничка добавления семени
	 *
	 * @return string
	 */
	public function actionNew()
	{
		/** @var SeedBull $model */
		$model = new SeedBull();

		$seedSupplierList = ArrayHelper::map(SeedSupplier::getAllList(), "id", "name");
		$breedList = Breed::getList();
		$colorList = ArrayHelper::map(Color::getAllList(), "id", "name");

		return $this->render('new',
			compact('model', 'seedSupplierList', 'breedList', 'colorList')
		);
	}

	/**
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionCreate()
	{
		/** @var SeedBull $model */
		$model = new SeedBull();

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
		    $model->birthday = (new \DateTime($model->birthday))->format('Y-m-d H:i:s');
			$model->save();
			Yii::$app->session->setFlash('success', 'Успешное создание семени');

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', 'Ошибка при создании семени');

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования данных о семени
	 *
	 * @return string
	 */
	public function actionEdit($id)
	{
		$model = SeedBull::findOne($id);

        $seedSupplierList = ArrayHelper::map(SeedSupplier::getAllList(), "id", "name");
        $breedList = Breed::getList();
        $colorList = ArrayHelper::map(Color::getAllList(), "id", "name");

		return $this->render('edit',
			compact('model', 'seedSupplierList', 'breedList', 'colorList')
		);
	}

	/**
	 * @param $id
	 * Обновление данных о семени
	 *
	 * @return string|\yii\web\Response
	 * @throws \Throwable
	 */
	public function actionUpdate($id)
	{
		/** @var SeedBull $model */
		$model = SeedBull::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
            $model->birthday = (new \DateTime($model->birthday))->format('Y-m-d H:i:s');
			$model->save();
			Yii::$app->session->setFlash('success', 'Успешное обновление данных о семени');

			return $this->redirect(["index"]);
		} else {
			Yii::$app->session->setFlash('error', 'Ошибка при обновлении данных о семени');

			return $this->render('edit',
				compact('model')
			);
		}
	}

    /**
     * Удаление семени
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
            /** @var SeedBull $model */
            $model = SeedBull::findOne($id);
            $model->delete();

            Yii::$app->session->setFlash('success', 'Успешное удаление семени');
            $transaction->commit();
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении семени');
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
	}
}