<?php

namespace backend\controllers;

use common\models\Suspension;
use yii\web\Controller;


class SuspensionsController extends Controller
{
    public function actionSuspensionAdd()
    {
        $model = new Suspension();

        $calfs = Calf::find()->indexBy('id')->orderBy(['id' => SORT_ASC])->column();

        return $this->render('suspension-add', [
            "model" => $model,
            "calfs" => $calfs
        ]);
    }

    public function actionSuspensionSave()
    {
        $model = new Suspension();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', 'Успешное добавление взвешивания');
            return $this->refresh();
        } else {
            return $this->render('suspension-add', [
                'model' => $model,
            ]);
        }
    }
}