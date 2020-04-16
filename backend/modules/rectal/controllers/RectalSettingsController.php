<?php

namespace backend\modules\rectal\controllers;

use common\models\rectal\RectalSettings;
use Yii;
use backend\controllers\BackendController;

/**
 * Class RectalSettingsController
 * @package backend\modules\rectal\controllers
 */
class RectalSettingsController extends BackendController
{
    /**
     * Настройки по РИ
     * @return string
     */
    public function actionIndex()
    {
        $model = RectalSettings::find()->one();
        return $this->render('index', compact('model'));
    }

    /**
     * Обновление настроек
     * 
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var RectalSettings $model */
        $model = RectalSettings::findOne($id);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', 'Успешное обновление настроек РИ');
            return $this->redirect(["index"]);
        } else {
            \Yii::$app->session->setFlash('error', 'Ошибка при обновлении настроек РИ');
            return $this->render('index',
                compact('model')
            );
        }
    }

}