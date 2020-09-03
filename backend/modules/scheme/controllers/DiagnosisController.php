<?php

namespace backend\modules\scheme\controllers;

use common\models\Animal;
use Yii;
use backend\modules\scheme\models\Diagnosis;
use backend\controllers\BackendController;
use backend\modules\scheme\models\search\DiagnosisSearch;
use yii\data\ActiveDataProvider;

/**
 * Class DiagnosisController
 * @package backend\modules\scheme\controllers
 */
class DiagnosisController extends BackendController
{
	/**
	 * Страничка со списком диагнозов
	 */
	public function actionIndex()
	{
		/** @var DiagnosisSearch $searchModel */
		$searchModel = new DiagnosisSearch();

		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			"searchModel"  => $searchModel,
			"dataProvider" => $dataProvider,
		]);
	}

	/**
	 * Страничка добавления нового диагноза
	 *
	 * @return string
	 */
	public function actionNew()
	{
		$model = new Diagnosis();

		return $this->render('new',
			compact("model")
		);
	}

	/**
	 * Создание диагноза
	 *
	 * @return string|Yii\web\Response
	 */
	public function actionCreate()
	{
		/** @var Diagnosis $model */
		$model = new Diagnosis();

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			$this->setFlash('success', Yii::t('app/diagnosis', 'DIAGNOSIS_CREATE_SUCCESS'));

			return $this->redirect(["index"]);
		} else {
			$this->setFlash('error', Yii::t('app/diagnosis', 'DIAGNOSIS_CREATE_ERROR'));

			return $this->render('new',
				compact("model")
			);
		}
	}

	/**
	 * @param $id
	 * Страничка редактирования диагноза
	 * @return string
	 */
	public function actionEdit($id)
	{
		$model = Diagnosis::findOne($id);

		return $this->render('edit',
			compact("model")
		);
	}

	/**
	 * @param $id
	 * Обновление диагноза
	 * @return string|Yii\web\Response
	 */
	public function actionUpdate($id)
	{
		/** @var Diagnosis $model */
		$model = Diagnosis::findOne($id);

		$isLoading = $model->load(Yii::$app->request->post());

		if ($isLoading && $model->validate()) {
			$model->save();
			$this->setFlash('success', Yii::t('app/diagnosis', 'DIAGNOSIS_EDIT_SUCCESS'));

			return $this->redirect(["index"]);
		} else {
			$this->setFlash('error', Yii::t('app/diagnosis', 'DIAGNOSIS_EDIT_ERROR'));

			return $this->render('edit',
				compact('model')
			);
		}
	}

	/**
	 * @param $id
	 * Удаление диагноза
	 * @return \yii\web\Response
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Animal::find()->where(['diagnosis' => $id])->exists()) {
                throw new \Exception('Этот диагноз удалить нельзя, потому что он используется и поставлен у животных');
            }

            /** @var Diagnosis $model */
            $model = Diagnosis::findOne($id);
            $model->delete();
            $transaction->commit();

            $this->setFlash('success', 'Успешное удаление диагноза');
            return $this->redirect(['index']);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            $this->setFlash('error', $exception->getMessage());
            return $this->redirect(['index']);
        }

	}
}
