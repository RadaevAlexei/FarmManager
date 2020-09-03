<?php

namespace backend\modules\reproduction\controllers;

use Yii;
use backend\modules\reproduction\models\SeedBull;
use backend\modules\reproduction\models\SeedBullStorage;
use backend\modules\reproduction\models\SeedCashBook;
use backend\modules\reproduction\models\search\SeedCashBookSearch;
use backend\modules\pharmacy\models\CashBook;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class SeedCashBookController
 * @package backend\modules\reproduction\controllers
 */
class SeedCashBookController extends BackendController
{
    /**
     * Страничка с данными по приходу и расходу
     */
    public function actionIndex()
    {
        /** @var SeedCashBookSearch $searchModel */
        $searchModel = new SeedCashBookSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * @param $type
     * @return string
     */
    public function actionNew($type)
    {
        /** @var SeedCashBook $model */
        $model = new SeedCashBook([
            'user_id' => Yii::$app->getUser()->id,
            'type'    => $type
        ]);

        return $this->render('new',
            compact('model')
        );
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        /** @var SeedCashBook $model */
        $model = new SeedCashBook();

        $isLoading = $model->load(Yii::$app->request->post());

        $model->date = (new \DateTime($model->date))->format('Y-m-d H:i:s');
        $seedBull = SeedBull::findOne($model->seed_bull_id);
        $totalPriceWithoutVat = 0;
        $totalPriceWithVat = 0;
        if ($seedBull) {
            $totalPriceWithoutVat = $seedBull->price * $model->count;
            $vatPercent = 0;
            if ($model->type == CashBook::TYPE_DEBIT) {
                $vatPercent = $model->vat_percent;
            }
            $model->vat_percent = $vatPercent;
            $totalPriceWithVat = $totalPriceWithoutVat * (1 + $vatPercent / 100);
        }
        $model->total_price_without_vat = $totalPriceWithoutVat;
        $model->total_price_with_vat = $totalPriceWithVat;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($isLoading && $model->validate()) {
                $model->save();

                /** @var SeedBullStorage $storage */
                $storage = SeedBullStorage::find()
                    ->where(['=', 'seed_bull_id', $model->seed_bull_id])
                    ->where(['=', 'container_duara_id', $model->container_duara_id])
                    ->one();

                if ($storage) {
                    $storage->updateAttributes([
                        'count' => ($model->type == CashBook::TYPE_KREDIT) ? ($storage->count - $model->count) : ($storage->count + $model->count)
                    ]);
                } else {
                    $preparationStorage = new SeedBullStorage();
                    $preparationStorage->seed_bull_id = $model->seed_bull_id;
                    $preparationStorage->container_duara_id = $model->container_duara_id;
                    $preparationStorage->count = (($model->type == SeedCashBook::TYPE_KREDIT) ? -$model->count : $model->count);
                    $preparationStorage->save();
                }

                $this->setFlash('success', 'Успешное добавление прихода');
                $transaction->commit();
            } else {
                $this->setFlash('error', 'Ошибка валидации');
            }
        } catch (\Exception $exception) {
            $this->setFlash('error', $exception->getMessage());
            $transaction->rollBack();
        }

        return $this->redirect(["index"]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /** @var CashBook $model */
            $model = CashBook::findOne($id);
            $model->delete();

            $this->setFlash('success', 'Успешное удаление');
            $transaction->commit();
        } catch (\Exception $exception) {
            $this->setFlash('error', $exception->getMessage());
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }
}
