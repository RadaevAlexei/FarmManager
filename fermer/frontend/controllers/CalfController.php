<?php

namespace frontend\controllers;


use common\models\Cow;
use common\models\Suspension;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class CalfController extends Controller
{
    public function actionIndex()
    {
        $query = Cow::find();

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
    public function actionDetail($number = null)
    {
        $calf = Cow::find()
            ->where(['number' => $number])
            ->one();

        $calfId = ArrayHelper::getValue($calf, "id", null);
        $calfSuspension = Suspension::find()
            ->where(['id' => $calfId])
            ->all();

        return $this->render('detail', [
            "calf" => $calf,
            "suspensions" => $calfSuspension
        ]);
    }
}