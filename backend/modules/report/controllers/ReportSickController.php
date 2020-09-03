<?php

namespace backend\modules\report\controllers;

use Yii;
use DateTime;
use DateTimeZone;
use backend\modules\report\models\forms\ReportSickForm;
use backend\controllers\BackendController;


/**
 * Class ReportSickController
 * @package backend\modules\report\controllers
 */
class ReportSickController extends BackendController
{
    /**
     *
     */
    public function actionIndex()
    {
        $model = new ReportSickForm();
        return $this->render('index', compact('model'));
    }

    /**
     * Скачивание отчета
     * @return string|\yii\console\Response|\yii\web\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionDownload()
    {
        $model = new ReportSickForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $dateTimeFrom = new DateTime($model->dateFrom, new DateTimeZone('Europe/Samara'));
            $dateTimeTo = new DateTime($model->dateFrom, new DateTimeZone('Europe/Samara'));

            if ($dateTimeFrom && $dateTimeTo && ($dateTimeFrom > $dateTimeTo)) {
                Yii::$app->session->setFlash('error', 'Дата начала периода должна быть меньше даты конца периода');
                return $this->redirect(['index']);
            }
            
            if ($model->type != ReportSickForm::DIAGRAM_TYPE_PIE && !$model->diagnosis_id) {
                Yii::$app->session->setFlash('error', 'Выберите диагноз');
                return $this->redirect(['index']);
            }

            $model->generate();
            return Yii::$app->response->sendFile($model->getFileName());

        } else {
            Yii::$app->session->setFlash('error', 'Ошибка валидации модели');
        }

        return $this->render('index');
    }
}
