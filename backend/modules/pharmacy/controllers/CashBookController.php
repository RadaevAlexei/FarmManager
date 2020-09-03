<?php

namespace backend\modules\pharmacy\controllers;

use backend\modules\pharmacy\models\Preparation;
use backend\modules\pharmacy\models\search\CashBookSearch;
use backend\modules\pharmacy\models\Storage;
use Yii;
use backend\modules\pharmacy\models\CashBook;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;

/**
 * Class CashBookController
 * @package backend\modules\pharmacy\controllers
 */
class CashBookController extends BackendController
{
    /**
     * Страничка с данными по приходу и расходу
     */
    public function actionIndex()
    {
        /** @var CashBookSearch $searchModel */
        $searchModel = new CashBookSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    public function actionNew($type)
    {
        /** @var CashBook $model */
        $model = new CashBook([
            'user_id' => Yii::$app->getUser()->id,
            'type'    => $type
        ]);

        return $this->render('new',
            compact('model')
        );
    }


    public function actionCreate()
    {
        /** @var CashBook $model */
        $model = new CashBook();

        $isLoading = $model->load(Yii::$app->request->post());

        $model->date = (new \DateTime($model->date))->format('Y-m-d H:i:s');
        $preparation = Preparation::findOne($model->preparation_id);
        $totalPriceWithoutVat = 0;
        $totalPriceWithVat = 0;
        $volume = 0;
        if ($preparation) {
            $totalPriceWithoutVat = $preparation->price * $model->count;
            $vatPercent = 0;
            if ($model->type == CashBook::TYPE_DEBIT) {
                $vatPercent = $model->vat_percent;
            }
            $model->vat_percent = $vatPercent;
            $totalPriceWithVat = $totalPriceWithoutVat * (1 + $vatPercent / 100);
            $volume = $preparation->volume;
        }
        $model->total_price_without_vat = $totalPriceWithoutVat;
        $model->total_price_with_vat = $totalPriceWithVat;
        $model->volume = $volume;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($isLoading && $model->validate()) {
                $model->save();

                /** @var Storage $storage */
                $storage = Storage::find()
                    ->where(['=', 'preparation_id', $model->preparation_id])
                    ->where(['=', 'stock_id', $model->stock_id])
                    ->andFilterWhere(['=', 'volume', $preparation->volume])
                    ->one();

                if ($storage) {
                    $storage->updateAttributes([
                        'count' => ($model->type == CashBook::TYPE_KREDIT) ? ($storage->count - $model->count) : ($storage->count + $model->count)
                    ]);
                } else {
                    $preparationStorage = new Storage();
                    $preparationStorage->preparation_id = $model->preparation_id;
                    $preparationStorage->stock_id = $model->stock_id;
                    $preparationStorage->count = (($model->type == CashBook::TYPE_KREDIT) ? -$model->count : $model->count);
                    $preparationStorage->volume = $model->volume;
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
