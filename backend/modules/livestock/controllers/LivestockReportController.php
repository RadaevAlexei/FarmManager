<?php

namespace backend\modules\livestock\controllers;

use Yii;
use backend\models\reports\ReportExcelPostingOffspring;
use backend\modules\livestock\models\Offspring;
use common\helpers\DataHelper;
use common\models\User;
use DateTime;
use DateTimeZone;
use Exception;
use \backend\controllers\BackendController;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use backend\modules\livestock\models\forms\LivestockSettingsForm;
use ZipArchive;

/**
 * Формирование отчетов
 *
 * Class LivestockReportController
 * @package backend\modules\livestock\controllers
 */
class LivestockReportController extends BackendController
{
    /**
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

        $reportTypes = ['Акт оприходования приплода'];
        $data = Offspring::getCalvingList($filterDateFrom, $filterDateTo);

        $disableReport = $data ? false : true;
        $dataProvider = new ArrayDataProvider(['allModels' => $data]);

        return $this->render('index', compact(
            'reportTypes',
            'dataProvider',
            'disableReport',
            'filterDateFrom',
            'filterDateTo'
        ));
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return string
     */
    public function actionSettingsReportForm($dateFrom = null, $dateTo = null)
    {
        $response = Yii::$app->response;

        $model = new LivestockSettingsForm();
        $model->dateFrom = $dateFrom;
        $model->dateTo = $dateTo;

        $organizationTypes = ['ООО "Агро-Нептун"', 'КФХ Калганов'];
        $usersList = ArrayHelper::map(User::getAllList(), "id", "lastName");
        $response->format = Response::FORMAT_HTML;

        return $this->renderPartial('forms/livestock-settings-form', compact(
            'model',
            'organizationTypes',
            'usersList'
        ));
    }

    /**
     * @return \yii\console\Response|Response
     */
    public function actionDownload()
    {
        if (Yii::$app->request->isPost) {
            $model = new LivestockSettingsForm();

            try {
                $model->load(Yii::$app->request->post());
                if (!$model->validate()) {
                    $message = DataHelper::getArrayString($model->getErrors());
                    throw new Exception($message);
                }

                $report = new ReportExcelPostingOffspring($model);
                $report->generate();
                return Yii::$app->response->sendFile($report->getNewFileName());
            } catch (Exception $exception) {
                $this->setFlash('error', $exception->getMessage());
                return $this->redirect(["index"]);
            }

        } else {
            $this->setFlash('error', 'Ошибка запроса');
            return $this->redirect(["index"]);
        }
    }

}
