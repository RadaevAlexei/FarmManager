<?php

namespace frontend\controllers;


use common\models\Calf;
use common\models\Suspension;
use yii\data\Pagination;
use yii\web\Controller;

class CalfController extends Controller
{
    public function actionIndex()
    {
        $query = Calf::find();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $calfs = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'calfs' => $calfs,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionDetail($id = null)
    {
        $calf = Calf::find()
            ->where(['id' => $id])
            ->one();

        $calfSuspension = Suspension::find()
            ->where(['id' => $id])
            ->all();

        return $this->render('detail', [
            "calf" => $calf,
            "suspensions" => $calfSuspension
        ]);
    }
}