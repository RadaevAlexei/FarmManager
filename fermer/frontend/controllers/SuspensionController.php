<?php

namespace frontend\controllers;


use common\models\Calf;
use yii\data\Pagination;
use yii\web\Controller;

class SuspensionController extends Controller
{
    public function actionIndex($groupId = null)
    {
        $calfs = Calf::find()->all();

        return $this->render('index', [
            'calfs' => $calfs
        ]);
    }
}