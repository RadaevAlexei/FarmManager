<?php

namespace backend\models\reports;

use Yii;
use backend\modules\reproduction\models\Insemination;
use common\helpers\DataHelper;
use common\models\rectal\Rectal;
use common\models\User;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\helpers\ArrayHelper;

/**
 * Отчет по ректальному исследованию за период
 *
 * Class ReportExcelRectalList
 * @package backend\models\reports
 */
class ReportExcelRectalList extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_rectal_list_for_report.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "rectal_list_for_report";
    const REPORT_DIRECTORY_REPORTS = "reports/rectal/rectal_list_reports/";

    private $dateFrom;
    private $dateTo;
    private $data;

    private $offset = 4;
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
        parent::__construct(
            self::REPORT_TEMPLATE_NAME,
            self::REPORT_DIRECTORY_REPORTS,
            self::REPORT_TEMPLATE_FILE_NAME
        );

        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->fetchData();
    }

    /**
     * Получение данных для генерации отчета
     */
    public function fetchData()
    {
        $this->data = Rectal::getRectalList($this->dateFrom, $this->dateTo);

        $this->reheats = ArrayHelper::map(
            Insemination::getReheatsList($this->dateFrom, $this->dateTo),
            "id",
            function ($item) {
                return $item;
            },
            "lastName"
        );
    }

    /**
     * Заполнение шапки отчета
     * @throws \Exception
     */
    public function fillHead()
    {
        $this->activeSheet()->setTitle('Отчет по РИ');

        $this->activeSheet()->setCellValue(
            "M1",
            (new DateTime('now', new DateTimeZone('Europe/Samara')))
                ->format('d.m.Y')
        );

        $this->activeSheet()->setCellValue(
            "M2",
            $this->getPeriodText($this->dateFrom, $this->dateTo)
        );
    }

    /**
     * Заполнение первой самой основной таблицы отчета
     * @throws Exception
     */
    public function fillFirstTable()
    {
        if (count($this->data) > 1) {
            $this->activeSheet()->insertNewRowBefore($this->offset + 1, count($this->data) - 1);
        }

        $index = 1;
        foreach ($this->data as $ri) {
            $this->activeSheet()->setCellValue("A$this->offset", $index);
            $this->activeSheet()->setCellValue("B$this->offset", ArrayHelper::getValue($ri, "collar"));
            $this->activeSheet()->setCellValue("C$this->offset", ArrayHelper::getValue($ri, "label"));
            $this->activeSheet()->setCellValue("D$this->offset",
                (new DateTime(
                    ArrayHelper::getValue($ri, "insemination_date"),
                    new DateTimeZone('Europe/Samara')
                ))->format('d.m.Y')
            );

            $userInsemination = ArrayHelper::getValue($ri, "insemination_lastname");
            $this->activeSheet()->setCellValue("E$this->offset", $userInsemination);

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

            $this->activeSheet()->setCellValue("F$this->offset",
                DataHelper::concatArrayIsNotEmptyElement([
                    ArrayHelper::getValue($ri, "bull_nickname"),
                    ArrayHelper::getValue($ri, "bull_number_1"),
                    ArrayHelper::getValue($ri, "bull_number_2"),
                    ArrayHelper::getValue($ri, "bull_number_3")
                ], "\n")
            );
            $this->activeSheet()->setCellValue("G$this->offset", ArrayHelper::getValue($ri, "count_insemination"));
            $this->activeSheet()->setCellValue("H$this->offset", ArrayHelper::getValue($ri, "insemination_count_dose"));
            $this->activeSheet()->getStyle("F$this->offset")->getAlignment()->setWrapText(true);
            $this->activeSheet()->setCellValue(
                "I$this->offset",
                Insemination::getTypeInseminationLabel(ArrayHelper::getValue($ri, "type_insemination"))
            );
            $resultRI = ArrayHelper::getValue($ri, "result");
            $this->activeSheet()->setCellValue(
                "J$this->offset",
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
            $this->activeSheet()->setCellValue("K$this->offset", $days ? $days : 0);
            $this->activeSheet()->setCellValue("L$this->offset",
                (new DateTime(
                    ArrayHelper::getValue($ri, "rectal_date"),
                    new DateTimeZone('Europe/Samara')
                ))->format('d.m.Y')
            );
            $this->activeSheet()->setCellValue("M$this->offset", ArrayHelper::getValue($ri, "rectal_lastname"));

            $servicePeriod = ArrayHelper::getValue($ri, "service_period");
            if ($servicePeriod) {
                $this->activeSheet()->setCellValue("N$this->offset", $servicePeriod);
                $this->usersServicePeriod[$userInsemination][] = $servicePeriod;
            }

            $index++;
            $this->offset++;
        }

        if (count($this->users) > 1) {
            $this->activeSheet()->insertNewRowBefore($this->offset + 1, count($this->users) - 1);
        }

        if (count($this->users) > 0) {
            $rangeData = $this->activeSheet()->rangeToArray("A$this->offset:M$this->offset");
            for ($i = 0; $i < count($this->users); $i++) {
                $this->activeSheet()->fromArray($rangeData, null, "A$this->offset");
                $this->activeSheet()->mergeCells("A$this->offset:B$this->offset");
                $this->activeSheet()->mergeCells("C$this->offset:E$this->offset");
                $this->activeSheet()->mergeCells("G$this->offset:L$this->offset");
                $this->activeSheet()->setCellValue("C$this->offset", $this->users[$i]);
                $userTotalDose = array_sum(ArrayHelper::getValue($this->usersDoses, $this->users[$i]));
                $this->usersTotalDoses[] = $userTotalDose;
                $this->activeSheet()->setCellValue("F$this->offset", $userTotalDose);
                $this->activeSheet()->setCellValue("M$this->offset",
                    DataHelper::getAverage(ArrayHelper::getValue($this->usersDoses, $this->users[$i]))
                );

                $servicePeriodAvg = DataHelper::getAverage(
                    ArrayHelper::getValue($this->usersServicePeriod, $this->users[$i])
                );
                $this->usersServicePeriodAvg[$this->users[$i]] = $servicePeriodAvg;
                $this->activeSheet()->setCellValue(
                    "N$this->offset",
                    $servicePeriodAvg
                );
                $this->offset++;
            }
        }

        $this->sumUsersTotalDoses = array_sum($this->usersTotalDoses);
        $this->activeSheet()->setCellValue("F$this->offset", $this->sumUsersTotalDoses);
        $this->activeSheet()->setCellValue(
            "N$this->offset",
            DataHelper::getAverage($this->usersServicePeriodAvg)
        );
    }

    /**
     * Заполнение второй таблицы отчета
     * @throws Exception
     */
    public function fillSecondTable()
    {
        $this->offset += 3;
        if (count($this->users) > 1) {
            $this->activeSheet()->insertNewRowBefore($this->offset + 1, count($this->users) - 1);
        }

        if (count($this->users) > 0) {
            for ($i = 0; $i < count($this->users); $i++) {
                $this->activeSheet()->mergeCells("B$this->offset:E$this->offset");
                $this->activeSheet()->mergeCells("G$this->offset:H$this->offset");
                $this->activeSheet()->mergeCells("I$this->offset:J$this->offset");
                $this->activeSheet()->mergeCells("K$this->offset:L$this->offset");

                $this->activeSheet()->setCellValue("A$this->offset", ($i + 1));
                $this->activeSheet()->setCellValue("B$this->offset", $this->users[$i]);

                $countAll = ArrayHelper::getValue($this->usersCount, $this->users[$i]) + count(ArrayHelper::getValue($this->reheats, $this->users[$i]));
                $this->totalInseminated += $countAll;
                $this->activeSheet()->setCellValue("F$this->offset", $countAll);

                $countSteriles = ArrayHelper::getValue($this->usersCountSteriles, $this->users[$i]);
                $this->activeSheet()->setCellValue("G$this->offset", $countSteriles ? $countSteriles : 0);
                $this->totalSteriles += $countSteriles;

                $countNotSteriles = ArrayHelper::getValue($this->usersCountNotSteriles, $this->users[$i]);
                $this->activeSheet()->setCellValue("I$this->offset", $countNotSteriles ? $countNotSteriles : 0);
                $this->totalNotSteriles += $countNotSteriles;

                $countReheats = count(ArrayHelper::getValue($this->reheats, $this->users[$i]));
                $this->totalReheats += $countReheats;
                $this->activeSheet()->setCellValue("K$this->offset", $countReheats);

                if ($countSteriles > 0 && $countAll > 0) {
                    $this->activeSheet()->setCellValue("M$this->offset", $countSteriles / $countAll);
                }

                $this->offset++;
            }
        }

        $this->activeSheet()->setCellValue("F$this->offset", $this->totalInseminated);
        $this->activeSheet()->setCellValue("G$this->offset", $this->totalSteriles);
        $this->activeSheet()->setCellValue("I$this->offset", $this->totalNotSteriles);
        $this->activeSheet()->setCellValue("K$this->offset", $this->totalReheats);
        $this->activeSheet()->setCellValue("M$this->offset", $this->totalSteriles / $this->totalInseminated);

        $this->offset++;
        if ($this->sumUsersTotalDoses && $this->totalSteriles) {
            $this->activeSheet()->setCellValue("F$this->offset", $this->sumUsersTotalDoses / $this->totalSteriles);
        }

        $this->offset++;
        if (count($this->users) > 1) {
            $this->activeSheet()->insertNewRowBefore($this->offset + 1, count($this->users) - 1);
        }

        if (count($this->users) > 0) {
            $indexRangeData = $this->activeSheet()->rangeToArray("A$this->offset:F$this->offset");
            for ($i = 0; $i < count($this->users); $i++) {
                $this->activeSheet()->mergeCells("A$this->offset:C$this->offset");
                $this->activeSheet()->mergeCells("D$this->offset:E$this->offset");
                $this->activeSheet()->mergeCells("F$this->offset:M$this->offset");

                if ($i != 0) {
                    $this->activeSheet()->fromArray($indexRangeData, null, "A$this->offset");
                }

                $this->activeSheet()->setCellValue("A$this->offset", $this->users[$i]);
                $this->activeSheet()->setCellValue("F$this->offset",
                    array_sum(ArrayHelper::getValue($this->usersDoses, $this->users[$i])) / ArrayHelper::getValue($this->usersCountSteriles, $this->users[$i])
                );

                $this->offset++;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function fillFooter()
    {
        $post = Yii::$app->request->post("RectalSettingsForm");
        $gynecologistId = ArrayHelper::getValue($post, "gynecologist");
        $gynecologist = User::findOne($gynecologistId);
        $chiefVeterinarianId = ArrayHelper::getValue($post, "chief_veterinarian");
        $chiefVeterinarian = User::findOne($chiefVeterinarianId);

        $this->offset += 5;
        $this->activeSheet()->setCellValue(
            "I$this->offset",
            ArrayHelper::getValue($gynecologist, "lastName")
        );

        $this->offset += 4;
        if (count($this->users) > 1) {
            $this->activeSheet()->insertNewRowBefore($this->offset + 1, (count($this->users) - 1) * 2);
        }

        if (count($this->users)) {
            $usersRangeData = $this->activeSheet()->rangeToArray("A$this->offset:I$this->offset");
            for ($i = 0; $i < count($this->users); $i++) {
                $this->activeSheet()->fromArray($usersRangeData, null, "A$this->offset");
                $this->activeSheet()->setCellValue(
                    "I$this->offset",
                    ArrayHelper::getValue($this->users, $i)
                );
                $this->offset += 2;
            }
        }

        $this->activeSheet()->setCellValue(
            "I$this->offset",
            ArrayHelper::getValue($chiefVeterinarian, "lastName")
        );
    }

    /**
     * @return mixed|void
     * @throws Exception
     */
    public function generate()
    {
        $this->fillHead();
        $this->fillFirstTable();
        $this->fillSecondTable();
        $this->fillFooter();
        $this->addNewFile();
    }
}