<?php

namespace backend\controllers;

use yii\web\Controller;


class CalfsController extends Controller
{
    /**
     * @return string
     */
    public function actionCalfAdd()
    {
        return $this->render('calf-add', [
            "model" => ""
        ]);
    }
}