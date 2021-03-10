<?php

namespace backend\models\reports;

use Yii;
use common\models\Animal;
use common\models\AnimalNote;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\Scheme;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class ReportExcelActionDay
 * @package backend\models\reports
 */
class ReportExcelActionDay extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_works_today.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "works_today";
    const REPORT_DIRECTORY_REPORTS = "reports/actions/actions_today/";

    private $data;
    private $filterDate;
    private $offset = 3;

    /**
     * ReportExcelActionDay constructor.
     * @param null $filterDate
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct($filterDate = null)
    {
        parent::__construct(
            self::REPORT_TEMPLATE_NAME,
            self::REPORT_DIRECTORY_REPORTS,
            self::REPORT_TEMPLATE_FILE_NAME
        );

        $this->initDate($filterDate);
        $this->fetchData();
    }

    /**
     * @param null $filterDate
     * @throws \Exception
     */
    private function initDate($filterDate = null)
    {
        /** @var DateTime $date */
        if (empty($filterDate)) {
            $this->filterDate = new DateTime('now', new DateTimeZone('Europe/Samara'));
        } else {
            $this->filterDate = new DateTime($filterDate);
        }
    }

    /**
     * Получение данных для генерации отчета
     */
    public function fetchData()
    {
        $this->data = ActionHistory::find()
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
                            $query->joinWith(['animalGroup', 'notes']);
                        },
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->where(['s.status' => Scheme::STATUS_ACTIVE]);
                            $query->joinWith(['diagnosis']);
                        }
                    ]);
                },
            ])
            ->where([
                'ah.scheme_day_at' => $this->filterDate->format('Y-m-d'),
                'ah.status'        => ActionHistory::STATUS_NEW
            ])
            ->all();
    }

    /**
     * @throws \Exception
     */
    public function fillHead()
    {
        $this->activeSheet()->setTitle('Список дел на сегодня');
        $this->activeSheet()->setCellValue(
            "H1", $this->filterDate->format('d.m.Y')
        );
    }

    /**
     * @throws Exception
     */
    public function fillMainTable()
    {
        $count = count($this->data);
        if ($count > 1) {
            $this->activeSheet()->insertNewRowBefore($this->offset + 1, $count - 1);
        }

        // Группируем по животным
        $grouped = [];
        foreach ($this->data as $item) {
            $animalId = ArrayHelper::getValue($item, "appropriationScheme.animal.id");
            $grouped[$animalId][] = $item;
        }

        foreach ($grouped as $animalId => $historyItem) {
            foreach ($historyItem as $index => $action) {
                /** @var Animal $animal */
                $animal = ArrayHelper::getValue($action, "appropriationScheme.animal");

                $this->activeSheet()->setCellValue(
                    "A$this->offset",
                    ArrayHelper::getValue($action, "appropriationScheme.scheme.name")
                );
                $this->activeSheet()->setCellValue(
                    "B$this->offset",
                    ArrayHelper::getValue($action, "scheme_day")
                );
                $this->activeSheet()->setCellValue(
                    "C$this->offset",
                    ArrayHelper::getValue($animal, "animalGroup.name")
                );
                $this->activeSheet()->setCellValue(
                    "D$this->offset",
                    ArrayHelper::getValue($animal, "collar")
                );
                $this->activeSheet()->setCellValue(
                    "E$this->offset",
                    ArrayHelper::getValue($animal, "label")
                );
                $this->activeSheet()->setCellValue(
                    "F$this->offset",
                    ArrayHelper::getValue($action, "appropriationScheme.scheme.diagnosis.name")
                );
                $this->activeSheet()->setCellValue(
                    "G$this->offset",
                    ArrayHelper::getValue($action, "groupsAction.name")
                );
                $this->activeSheet()->setCellValue(
                    "H$this->offset",
                    ArrayHelper::getValue($action, "action.name")
                );

                /** @var AnimalNote $lastNote */
                $lastNote = $animal->getLastNote();
                $lastNoteText = ArrayHelper::getValue($lastNote, "description");

                if (!$index) {
                    $this->activeSheet()->setCellValue(
                        "J$this->offset",
                        $lastNoteText
                    );
                }

                $this->activeSheet()->getRowDimension($this->offset)->setRowHeight(-1);

                $this->offset++;
            }
        }

        $end = $this->offset + 1;

        $this->activeSheet()->getStyle("A3:J$end")->getFont()->setBold(false);
        $this->activeSheet()->getProtection()->setSheet(false);
        $this->activeSheet()->getPageSetup()->setScale(100);
        $this->activeSheet()->getColumnDimension('C')->setAutoSize(true);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function generate()
    {
        $this->fillHead();
        $this->fillMainTable();
        $this->addNewFile();
    }
}
