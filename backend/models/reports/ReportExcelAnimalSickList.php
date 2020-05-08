<?php

namespace backend\models\reports;

use Yii;
use backend\modules\scheme\models\AppropriationScheme;
use backend\modules\scheme\models\Scheme;
use common\models\Animal;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Отчет по больным животным
 *
 * Class ReportExcelAnimalSickList
 * @package backend\models\reports
 */
class ReportExcelAnimalSickList extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_animal_sick_list.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "animal_sick_list";
    const REPORT_DIRECTORY_REPORTS = "reports/animal/animal_sick_list";

    private $data;

    private $offset = 4;

    /**
     * ReportExcelAnimalSickList constructor.
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct()
    {
        parent::__construct(self::REPORT_TEMPLATE_NAME);
        $this->fetchData();
    }

    /**
     * Получение данных для генерации отчета
     */
    public function fetchData()
    {
        $this->data = Animal::find()
            ->alias('a')
            ->with([
                'diagnoses'           => function (ActiveQuery $query) {
                    $query->alias('d');
                },
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->where(['s.status' => Scheme::STATUS_ACTIVE]);
                            $query->joinWith([
                                'schemeDays' => function (ActiveQuery $query) {
                                    $query->alias('sd');
                                }
                            ]);
                        }
                    ]);
                    $query->andFilterWhere(['as.status' => AppropriationScheme::STATUS_IN_PROGRESS]);
                    $query->orderBy(['as.started_at' => SORT_ASC]);
                }
            ])
            ->where([
                'a.health_status' => Animal::HEALTH_STATUS_SICK
            ])->all();
    }

    /**
     * @throws \Exception
     */
    public function fillHead()
    {
        $this->sheet->setTitle('Список больных животных');

        $this->sheet->setCellValue(
            "G1",
            (new DateTime('now', new DateTimeZone('Europe/Samara')))
                ->format('d.m.Y')
        );
    }

    /**
     * @throws Exception
     */
    public function fillMainTable()
    {
        $count = count($this->data);
        if ($count > 1) {
            $this->sheet->insertNewRowBefore($this->offset, $count - 1);
        }

        foreach ($this->data as $index => $animal) {
            $this->sheet->setCellValue("A$this->offset", $index + 1);
            $this->sheet->setCellValue("B$this->offset",
                ArrayHelper::getValue($animal, "collar")
            );
            $this->sheet->setCellValue(
                "C$this->offset",
                ArrayHelper::getValue($animal, "label")
            );
            $this->sheet->setCellValue(
                "D$this->offset",
                (new DateTime(ArrayHelper::getValue($animal,
                    "date_health")))->format('d.m.Y')
            );
            $this->sheet->setCellValue(
                "E$this->offset",
                ArrayHelper::getValue($animal, "diagnoses.name")
            );
            $this->sheet->setCellValue(
                "F$this->offset",
                (new DateTime(ArrayHelper::getValue($animal,
                    "appropriationScheme.started_at")))->format('d.m.Y')
            );

            /** @var AppropriationScheme[] $appropriationSchemes */
            $appropriationSchemes = $animal->onScheme();

            $appropriationSchemesResult = '';
            foreach ($appropriationSchemes as $appropriationScheme) {
                $appropriationSchemesResult .= ArrayHelper::getValue($appropriationScheme, "scheme.name") . "\n";
            }

            $this->sheet->setCellValue("G$this->offset", $appropriationSchemesResult);
            $this->sheet->setCellValue("H$this->offset",
                count(ArrayHelper::getValue($animal, "appropriationScheme.scheme.schemeDays"))
            );

            $this->offset++;
        }

        $end = $this->offset + 1;
        $this->sheet->getStyle("A4:H$end")->getAlignment()->setWrapText(true);
        $this->sheet->getStyle("A4:H$end")
            ->getFont()->setBold(false)->setSize(10);
        $this->sheet->getColumnDimension('G')->setAutoSize(true);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function generate()
    {
        $this->fillHead();
        $this->fillMainTable();
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function saveReport()
    {
        self::save(
            self::REPORT_DIRECTORY_REPORTS,
            self::REPORT_TEMPLATE_FILE_NAME
        );
    }

    /**
     * @return mixed|void
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateAndSave()
    {
        $this->generate();
        $this->saveReport();
    }
}