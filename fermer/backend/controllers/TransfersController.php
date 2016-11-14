<?php

namespace backend\controllers;

use common\helpers\DataHelper;
use common\models\Functions;
use common\models\Groups;
use common\models\Transfers;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Class TransfersController
 * @package backend\controllers
 */
class TransfersController extends Controller
{
    /**
     * Список переводов
     * @return string
     */
    public function actionList()
    {
        $query = Transfers::find()->with(
            ['groupFrom', 'groupTo', 'calfInfo']
        );

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $transfers = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $this->viewListDataTransfers($transfers);

        return $this->render('transfers', [
            'transfers' => $transfers,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param null $transfers
     */
    private function viewListDataTransfers(&$transfers = null)
    {
        if (empty($transfers)) {
            return;
        }

        foreach ($transfers as &$transfer) {
            $this->viewListDataTransfer($transfer);
        }
    }

    /**
     * @param null $transfer
     */
    private function viewListDataTransfer(&$transfer = null)
    {
        if (empty($transfer)) {
            return;
        }

        $transfer["date"] = DataHelper::getDate(ArrayHelper::getValue($transfer, "date"));
        $transfer["name"] = DataHelper::concatArrayIsNotEmptyElement([
            ArrayHelper::getValue($transfer, "calfInfo.nickname"),
            ArrayHelper::getValue($transfer, "calfInfo.number")
        ], ' - №');
    }

    /**
     * @param null $action
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionActions($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/transfers"]);
        } else {
            if ($action == "new") {
                $model = new Transfers();
                $url = Url::toRoute(['/transfer/save/']);
            } else if ($action == "edit") {
                $model = Transfers::find()->where(['id' => $id])->one();
                $url = Url::toRoute(['/transfer/update/' . $id . '/']);
            } else if ($action == "delete") {
                $model = Transfers::find()->where(['id' => $id])->one();
                $model->delete();
                return $this->redirect(['/transfers']);
            }
        }

        $groups = Groups::find()->select(['name', 'employeeId', 'id'])->indexBy("id")->orderBy(['id' => SORT_ASC])->column();

        return $this->render('transfer-add', [
            "action" => $action,
            "url" => $url,
            "model" => $model,
            "groups" => $groups
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSaveUpdate($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/transfers"]);
        } else {
            if ($action == "save") {
                $model = new Transfers();
            } else if ($action == "update") {
                //TODO:: Добавить все поля в сравнение
                $model = Transfers::find()->where(['id' => $id])->one();
            } else {
                throw new NotFoundHttpException("Такого действия нет");
            }
        }

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', \Yii::t('app/back', 'TRANSFER_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(["/transfers"]);
        } else {
            return $this->render('transfer-add', [
                'model' => $model,
            ]);
        }
    }
}