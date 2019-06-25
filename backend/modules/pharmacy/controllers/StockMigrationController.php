<?php

namespace backend\modules\pharmacy\controllers;

use backend\modules\pharmacy\models\Preparation;
use backend\modules\pharmacy\models\Stock;
use backend\modules\pharmacy\models\links\StockPreparationLink;
use backend\modules\pharmacy\models\Storage;
use common\models\User;
use Yii;
use backend\modules\pharmacy\models\StockMigration;
use backend\modules\pharmacy\models\search\StockMigrationSearch;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class StockMigrationController
 * @package backend\modules\pharmacy\controllers
 */
class StockMigrationController extends BackendController
{
    /**
     * Страничка со списком перемещений
     */
    public function actionIndex()
    {
        /** @var StockMigrationSearch $searchModel */
        $searchModel = new StockMigrationSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Страничка добавления нового перемещения
     *
     * @return string
     */
    public function actionNew()
    {
        /** @var StockMigration $model */
        $model = new StockMigration([
            'user_id' => Yii::$app->getUser()->id
        ]);

        $userList = ArrayHelper::map(User::getAllList(), "id", "lastName");
        $preparationList = ArrayHelper::map(Preparation::getAllList(), "id", "name");
        $stockList = ArrayHelper::map(Stock::getAllList(), "id", "name");

        return $this->render('new',
            compact('model', 'userList', 'preparationList', 'stockList')
        );
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCreateMigration()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /** @var StockMigration $model */
            $model = new StockMigration();

            $isLoading = $model->load(Yii::$app->request->post());

            if ($isLoading && $model->validate()) {

                /** @var Storage $storageFrom */
                $storageFrom = Storage::find()
                    ->where([
                        'preparation_id' => $model->preparation_id,
                        'stock_id'       => $model->stock_from_id,
                        'volume'         => $model->volume,
                    ])
                    ->andWhere(['>=', 'count', $model->count])
                    ->one();

                if (!$storageFrom) {
                    throw new \Exception('Ошибка при перемещении препарата. Препарата в такой количестве и объёме на этом складе нет!');
                }

                /** @var Storage $storageTo */
                $storageTo = Storage::find()
                    ->where([
                        'preparation_id' => $model->preparation_id,
                        'stock_id'       => $model->stock_to_id,
                        'volume'         => $model->volume,
                    ])
                    ->one();

                if ($storageTo) {
                    $storageTo->updateAttributes([
                        'count' => $storageTo->count + $model->count
                    ]);
                } else {
                    $storageTo = new Storage([
                        'preparation_id' => $model->preparation_id,
                        'stock_id'       => $model->stock_to_id,
                        'count'          => $model->count,
                        'volume'         => $model->volume
                    ]);
                    $storageTo->save();
                }

                if ($storageFrom->count != $model->count) {
                    $storageFrom->updateAttributes([
                        'count' => $storageFrom->count - $model->count
                    ]);
                } else {
                    $storageFrom->delete();
                }

                $model->save();
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Успешное перемещение');
            } else {
                throw new \Exception('Ошибка при перемещении');
            }

            return $this->redirect(["new"]);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect(["new"]);
        }
    }
}