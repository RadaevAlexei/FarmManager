<?php

namespace backend\models\reports;

use Yii;
use backend\modules\reproduction\models\Insemination;
use common\helpers\DataHelper;
use common\helpers\DateHelper;
use common\models\rectal\Rectal;
use common\models\User;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class ReportExcelRectalList
 * @package backend\models\reports
 */
class ReportExcelRectalList extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_rectal_list_for_report.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "rectal_list_for_report";
    const REPORT_DIRECTORY_REPORTS = "reports/rectal/rectal-list/rectal_list_reports";

    private $dateFrom;
    private $dateTo;
    private $data;

    private $totalReheats = 0;
    private $users = [];
    private $usersCount = [];
    private $usersDoses = [];
    private $usersTotalDoses = [];
    private $usersServicePeriod = [];
    private $usersServicePeriodAvg = [];
    private $usersCountSteriles = [];
    private $usersCountNotSteriles = [];
    private $totalInseminated = 0;
    private $totalSteriles = 0;
    private $totalNotSteriles = 0;
    private $sumUsersTotalDoses = 0;
    private $reheats = [];

    /**
     * ReportExcelRectalList constructor.
     * @param null $dateFrom
     * @param null $dateTo
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct($dateFrom = null, $dateTo = null)
    {
        parent::__construct(self::getPathTemplateForReport());

        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->fetchData();
    }

    /**
     * @return string
     */
    private function getPathTemplateForReport()
    {
        return $this->getFullPath(self::REPORT_TEMPLATE_NAME);
    }

    /**
     * Получение данных для генерации отчета
     */
    private function fetchData()
    {
        $this->data = Rectal::getRectalList($this->dateFrom, $this->dateTo);
    }

    /**
     * @return mixed|void
     * @throws Exception
     */
    public function generate()
    {
        $this->sheet->setTitle('Отчет по РИ');

        $this->sheet->setCellValue(
            "M1",
            (new DateTime('now', new DateTimeZone('Europe/Samara')))
                ->format('d.m.Y')
        );

        $this->sheet->setCellValue("M2", $this->getPeriodText($this->dateFrom, $this->dateTo));

        $this->reheats = ArrayHelper::map(
            Insemination::getReheatsList($this->dateFrom, $this->dateTo),
            "id",
            function ($item) {
                return $item;
            },
            "lastName"
        );

        $count = count($this->data);

        $startIndex = 5;
        if ($count > 1) {
            $this->sheet->insertNewRowBefore($startIndex, $count - 1);
        }

        $offset = 4;
        $index = 1;
        foreach ($this->data as $ri) {
            $this->sheet->setCellValue("A$offset", $index);
            $this->sheet->setCellValue("B$offset", ArrayHelper::getValue($ri, "collar"));
            $this->sheet->setCellValue("C$offset", ArrayHelper::getValue($ri, "label"));
            $this->sheet->setCellValue("D$offset",
                (new DateTime(
                    ArrayHelper::getValue($ri, "insemination_date"),
                    new DateTimeZone('Europe/Samara')
                ))->format('d.m.Y')
            );

            $userInsemination = ArrayHelper::getValue($ri, "insemination_lastname");
            $this->sheet->setCellValue("E$offset", $userInsemination);

            if (!in_array($userInsemination, $this->users)) {
                $this->users[] = $userInsemination;
            }
            if (!isset($this->usersCount[$userInsemination])) {
                $this->usersCount[$userInsemination] = 1;
            } else {
                $this->usersCount[$userInsemination]++;
            }
            $dose = ArrayHelper::getValue($ri, "insemination_count_dose");
            $this->usersDoses[$userInsemination][] = $dose;

            $this->sheet->setCellValue("F$offset",
                DataHelper::concatArrayIsNotEmptyElement([
                    ArrayHelper::getValue($ri, "bull_nickname"),
                    ArrayHelper::getValue($ri, "bull_number_1"),
                    ArrayHelper::getValue($ri, "bull_number_2"),
                    ArrayHelper::getValue($ri, "bull_number_3")
                ], "\n")
            );
            $this->sheet->setCellValue("G$offset", ArrayHelper::getValue($ri, "count_insemination"));
            $this->sheet->setCellValue("H$offset", ArrayHelper::getValue($ri, "insemination_count_dose"));
            $this->sheet->getStyle("F$offset")->getAlignment()->setWrapText(true);
            $this->sheet->setCellValue(
                "I$offset",
                Insemination::getTypeInseminationLabel(ArrayHelper::getValue($ri, "type_insemination"))
            );
            $resultRI = ArrayHelper::getValue($ri, "result");
            $this->sheet->setCellValue(
                "J$offset",
                $resultRI
            );
            if ($resultRI) {
                if (!isset($this->usersCountSteriles[$userInsemination])) {
                    $this->usersCountSteriles[$userInsemination] = 1;
                } else {
                    $this->usersCountSteriles[$userInsemination]++;
                }
            } else {
                if (!isset($this->usersCountNotSteriles[$userInsemination])) {
                    $this->usersCountNotSteriles[$userInsemination] = 1;
                } else {
                    $this->usersCountNotSteriles[$userInsemination]++;
                }
            }

            $days = ArrayHelper::getValue($ri, "days");
            $this->sheet->setCellValue("K$offset", $days ? $days : 0);
            $this->sheet->setCellValue("L$offset",
                (new DateTime(
                    ArrayHelper::getValue($ri, "rectal_date"),
                    new DateTimeZone('Europe/Samara')
                ))->format('d.m.Y')
            );
            $this->sheet->setCellValue("M$offset", ArrayHelper::getValue($ri, "rectal_lastname"));

            $servicePeriod = ArrayHelper::getValue($ri, "service_period");
            if ($servicePeriod) {
                $this->sheet->setCellValue("N$offset", $servicePeriod);
                $this->usersServicePeriod[$userInsemination][] = $servicePeriod;
            }

            $index++;
            $offset++;
        }

        if (count($this->users) > 1) {
            $this->sheet->insertNewRowBefore($offset + 1, count($this->users) - 1);
        }

        if (count($this->users) > 0) {
            $rangeData = $this->sheet->rangeToArray("A$offset:M$offset");
            for ($i = 0; $i < count($this->users); $i++) {
                $this->sheet->fromArray($rangeData, null, "A$offset");
                $this->sheet->mergeCells("A$offset:B$offset");
                $this->sheet->mergeCells("C$offset:E$offset");
                $this->sheet->mergeCells("G$offset:L$offset");
                $this->sheet->setCellValue("C$offset", $this->users[$i]);
                $userTotalDose = array_sum(ArrayHelper::getValue($this->usersDoses, $this->users[$i]));
                $this->usersTotalDoses[] = $userTotalDose;
                $this->sheet->setCellValue("F$offset", $userTotalDose);
                $this->sheet->setCellValue("M$offset",
                    DataHelper::getAverage(ArrayHelper::getValue($this->usersDoses, $this->users[$i]))
                );

                $servicePeriodAvg = DataHelper::getAverage(ArrayHelper::getValue($this->usersServicePeriod, $this->users[$i]));
                $this->usersServicePeriodAvg[$this->users[$i]] = $servicePeriodAvg;
                $this->sheet->setCellValue(
                    "N$offset",
                    $servicePeriodAvg
                );
                $offset++;
            }
        }

        $this->sumUsersTotalDoses = array_sum($this->usersTotalDoses);
        $this->sheet->setCellValue("F$offset", $this->sumUsersTotalDoses);
        $this->sheet->setCellValue(
            "N$offset",
            DataHelper::getAverage($this->usersServicePeriodAvg)
        );

        $offset += 3;
        if (count($this->users) > 1) {
            $this->sheet->insertNewRowBefore($offset + 1, count($this->users) - 1);
        }

        if (count($this->users) > 0) {
            for ($i = 0; $i < count($this->users); $i++) {
                $this->sheet->mergeCells("B$offset:E$offset");
                $this->sheet->mergeCells("G$offset:H$offset");
                $this->sheet->mergeCells("I$offset:J$offset");
                $this->sheet->mergeCells("K$offset:L$offset");

                $this->sheet->setCellValue("A$offset", ($i + 1));
                $this->sheet->setCellValue("B$offset", $this->users[$i]);

                $countAll = ArrayHelper::getValue($this->usersCount, $this->users[$i]) + count(ArrayHelper::getValue($this->reheats, $this->users[$i]));
                $this->totalInseminated += $countAll;
                $this->sheet->setCellValue("F$offset", $countAll);

                $countSteriles = ArrayHelper::getValue($this->usersCountSteriles, $this->users[$i]);
                $this->sheet->setCellValue("G$offset", $countSteriles ? $countSteriles : 0);
                $this->totalSteriles += $countSteriles;

                $countNotSteriles = ArrayHelper::getValue($this->usersCountNotSteriles, $this->users[$i]);
                $this->sheet->setCellValue("I$offset", $countNotSteriles ? $countNotSteriles : 0);
                $this->totalNotSteriles += $countNotSteriles;

                $countReheats = count(ArrayHelper::getValue($this->reheats, $this->users[$i]));
                $this->totalReheats += $countReheats;
                $this->sheet->setCellValue("K$offset", $countReheats);

                if ($countSteriles > 0 && $countAll > 0) {
                    $this->sheet->setCellValue("M$offset", $countSteriles / $countAll);
                }

                $offset++;
            }
        }

        $this->sheet->setCellValue("F$offset", $this->totalInseminated);
        $this->sheet->setCellValue("G$offset", $this->totalSteriles);
        $this->sheet->setCellValue("I$offset", $this->totalNotSteriles);
        $this->sheet->setCellValue("K$offset", $this->totalReheats);
        $this->sheet->setCellValue("M$offset", $this->totalSteriles / $this->totalInseminated);

        $offset++;
        if ($this->sumUsersTotalDoses && $this->totalSteriles) {
            $this->sheet->setCellValue("F$offset", $this->sumUsersTotalDoses / $this->totalSteriles);
        }

        $offset++;
        if (count($this->users) > 1) {
            $this->sheet->insertNewRowBefore($offset + 1, count($this->users) - 1);
        }

        if (count($this->users) > 0) {
            $indexRangeData = $this->sheet->rangeToArray("A$offset:F$offset");
            for ($i = 0; $i < count($this->users); $i++) {
                $this->sheet->mergeCells("A$offset:C$offset");
                $this->sheet->mergeCells("D$offset:E$offset");
                $this->sheet->mergeCells("F$offset:M$offset");

                if ($i != 0) {
                    $this->sheet->fromArray($indexRangeData, null, "A$offset");
                }

                $this->sheet->setCellValue("A$offset", $this->users[$i]);
                $this->sheet->setCellValue("F$offset",
                    array_sum(ArrayHelper::getValue($this->usersDoses, $this->users[$i])) / ArrayHelper::getValue($this->usersCountSteriles, $this->users[$i])
                );

                $offset++;
            }
        }

        $post = Yii::$app->request->post("RectalSettingsForm");
        $gynecologistId = ArrayHelper::getValue($post, "gynecologist");
        $gynecologist = User::findOne($gynecologistId);
        $chiefVeterinarianId = ArrayHelper::getValue($post, "chief_veterinarian");
        $chiefVeterinarian = User::findOne($chiefVeterinarianId);

        $offset += 5;
        $this->sheet->setCellValue(
            "I$offset",
            ArrayHelper::getValue($gynecologist, "lastName")
        );

        $offset += 4;
        if (count($this->users) > 1) {
            $this->sheet->insertNewRowBefore($offset + 1, (count($this->users) - 1) * 2);
        }

        if (count($this->users)) {
            $usersRangeData = $this->sheet->rangeToArray("A$offset:I$offset");
            for ($i = 0; $i < count($this->users); $i++) {
                $this->sheet->fromArray($usersRangeData, null, "A$offset");
                $this->sheet->setCellValue(
                    "I$offset",
                    ArrayHelper::getValue($this->users, $i)
                );
                $offset += 2;
            }
        }

        $this->sheet->setCellValue(
            "I$offset",
            ArrayHelper::getValue($chiefVeterinarian, "lastName")
        );
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
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateAndSave()
    {
        $this->generate();
        $this->saveReport();
    }
}