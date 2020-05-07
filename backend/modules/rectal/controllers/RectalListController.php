<?php

namespace backend\modules\rectal\controllers;

use Yii;
use backend\models\reports\ReportExcelRectalList;
use common\models\rectal\Rectal;
use common\models\User;
use DateTime;
use DateTimeZone;
use Exception;
use backend\models\reports\ReportExcelGynecologist;
use backend\controllers\BackendController;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class RectalListController
 * @package backend\modules\rectal\controllers
 */
class RectalListController extends BackendController
{
    /**
     * Страничка списка животных попадающих под ректальное исследование
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {
        $filterDateFrom = null;
        $filterDateTo = null;

        if (Yii::$app->request->isPost) {
            $dateFromPost = Yii::$app->request->post("filter_date_from");
            $dateToPost = Yii::$app->request->post("filter_date_to");

            if (!$dateFromPost && !$dateToPost) {
                $filterDateFrom = (new DateTime('now', new DateTimeZone('Europe/Samara')));
            } else {
                if ($dateFromPost) {
                    $filterDateFrom = (new DateTime($dateFromPost, new DateTimeZone('Europe/Samara')));
                }
                if ($dateToPost) {
                    $filterDateTo = (new DateTime($dateToPost, new DateTimeZone('Europe/Samara')));
                }
            }
        } else {
            $filterDateFrom = (new DateTime('now', new DateTimeZone('Europe/Samara')));
        }

        $filterDateFrom = $filterDateFrom ? $filterDateFrom->format('Y-m-d') : null;
        $filterDateTo = $filterDateTo ? $filterDateTo->format('Y-m-d') : null;

        $listGynecologist = Rectal::getRectalListForGynecologist($filterDateFrom, $filterDateTo);
        $dataProvider = new ArrayDataProvider(['allModels' => $listGynecologist]);

        $list = Rectal::getRectalList($filterDateFrom, $filterDateTo);
        $disableReport = $list ? false : true;

        return $this->render('index', compact(
            'disableReport',
            'dataProvider',
            'filterDateFrom',
            'filterDateTo'
        ));
    }

    /**
     * Скачивание отчета для гинеколога
     *
     * @param null $dateFrom
     * @param null $dateTo
     * @return \yii\console\Response|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function actionDownloadRectalListGynecologist($dateFrom = null, $dateTo = null)
    {
        $report = new ReportExcelGynecologist($dateFrom, $dateTo);
        $report->generateAndSave();

        return Yii::$app->response->sendFile($report->getNewFileName());
    }

    /**
     * Скачивание отчета по РИ
     *
     * @param null $dateFrom
     * @param null $dateTo
     * @return \yii\console\Response|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function actionDownloadRectalList($dateFrom = null, $dateTo = null)
    {
        $report = new ReportExcelRectalList($dateFrom, $dateTo);
        $report->generateAndSave();

        return Yii::$app->response->sendFile($report->getNewFileName());
    }

    /**
     * Настройки отчета по РИ
     *
     * @param null $dateFrom
     * @param null $dateTo
     * @return string
     */
    public function actionSettingsRectalReportForm($dateFrom = null, $dateTo = null)
    {
        $response = Yii::$app->response;

        $usersList = ArrayHelper::map(User::getAllList(), "id", "lastName");

        $response->format = Response::FORMAT_HTML;

        return $this->renderPartial('forms/rectal-settings-form', compact(
            'model',
            'dateFrom',
            'dateTo',
            'usersList'
        ));
    }
}