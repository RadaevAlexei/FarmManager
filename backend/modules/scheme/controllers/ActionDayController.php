<?php

namespace backend\modules\scheme\controllers;

use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\search\ActionHistorySearch;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Yii;
use PhpOffice\PhpSpreadsheet\IOFactory;
use backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;


/**
 * Class ActionDayController
 * @package backend\modules\scheme\controllers
 */
class ActionDayController extends BackendController
{
    const TEMPLATE_NAME = "template_works_today.xlsx";
    const TEMPLATE_FILE_NAME = "works_today";
    const DIRECTORY_REPORTS = "actions_today";

    const READER_TYPE = "Xlsx";
    const WRITER_TYPE = "Xlsx";

    /**
     * Страничка со списком схем, в которых нужно что-то сделать
     */
    public function actionIndex()
    {
        /** @var ActionHistorySearch $searchModel */
//        $searchModel = new ActionHistorySearch();
        
        /** @var ArrayDataProvider $dataProvider */
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            compact('searchModel')
        );
    }

    private function getPathTemplate()
    {
        return Yii::getAlias('@webroot') . '/templates/' . self::TEMPLATE_NAME;
    }

    private function getTimePrefix()
    {
        return (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y_m_d_H_i_s');
    }

    public function actionDownloadActionList()
    {
        /** @var BaseReader $reader */
        $templatePath = $this->getPathTemplate();

        $reader = new Xlsx();

        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = $reader->load($templatePath);

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("H1", (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('d-m-Y'));

        /** @var ActionHistory[] $history */
        $history = ActionHistory::find()
            ->alias('ah')
            ->select(['ah.*', 'as.scheme_id', 'as.animal_id'])
            ->joinWith([
                'groupsAction',
                'action',
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'animal' => function (ActiveQuery $query) {
                            $query->alias('a');
                            $query->joinWith(['animalGroup']);
                        },
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->joinWith(['diagnosis']);
                        }
                    ]);
                },
            ])
            ->where([
                'ah.scheme_day_at' => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d')
            ])
            ->all();

        $count = count($history);
        if ($count > 1) {
            $sheet->insertNewRowBefore(3, $count - 1);
        }

        $offset = 3;
        foreach ($history as $action) {
            $sheet->setCellValue("A$offset", ArrayHelper::getValue($action, "appropriationScheme.scheme.name"));
            $sheet->setCellValue("B$offset", ArrayHelper::getValue($action, "scheme_day"));
            $sheet->setCellValue("C$offset",
                ArrayHelper::getValue($action, "appropriationScheme.animal.animalGroup.name"));
            $sheet->setCellValue("D$offset", ArrayHelper::getValue($action, "appropriationScheme.animal.label"));
            $sheet->setCellValue("E$offset", ArrayHelper::getValue($action, "appropriationScheme.animal.label"));
            $sheet->setCellValue("F$offset",
                ArrayHelper::getValue($action, "appropriationScheme.scheme.diagnosis.name"));
            $sheet->setCellValue("G$offset", ArrayHelper::getValue($action, "groupsAction.name"));
            $sheet->setCellValue("H$offset", ArrayHelper::getValue($action, "action.name"));
            $offset++;
        }

        $end = $offset + 1;
        $spreadsheet->getActiveSheet()->getStyle("A3:J$end")->getFont()->setBold(false);

        $sheet->setTitle('Список дел на сегодня');

        $writer = IOFactory::createWriter($spreadsheet, self::WRITER_TYPE);
        $prefix = $this->getTimePrefix();
        $newFileName = self::DIRECTORY_REPORTS . "/" . self::TEMPLATE_FILE_NAME . '_' . $prefix . '.xlsx';
        $writer->save($newFileName);

        return Yii::$app->response->sendFile($newFileName);
    }
}