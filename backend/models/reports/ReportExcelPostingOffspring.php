<?php

namespace backend\models\reports;

use Yii;
use common\helpers\DateHelper;
use common\models\Animal;
use DateTime;
use DateTimeZone;
use backend\modules\livestock\models\Offspring;
use common\helpers\DataHelper;
use common\models\User;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\helpers\ArrayHelper;

/**
 * Акт на оприходование приплода
 *
 * Class ReportExcelPostingOffspring
 * @package backend\models\reports
 */
class ReportExcelPostingOffspring extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_posting_offspring.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "posting_offspring";
    const REPORT_DIRECTORY_REPORTS = "reports/animal/posting_offspring/";

    private $data;

    private $dateFrom;
    private $dateTo;
    private $organization;
    private $department;
    private $foreman;
    private $livestockSpecialist;

    private $maxCountInOneReport = 17;
    private $dataReports = [];

    const FIRST_REPORT = 0;
    const SECOND_REPORT = 1;
    const FIRST_PAGE = 0;
    const SECOND_PAGE = 1;

    /**
     * @var int
     */
    private $curReport = 0;

    /**
     * @var array
     */
    private $fields = [
        self::FIRST_REPORT  => [
            self::FIRST_PAGE  => [
                "date"         => [
                    "day"   => "S8",
                    "month" => "T8",
                    "year"  => "U8",
                ],
                "organization" => "D9",
                "department"   => "C10",
                "foreman"      => "G13",
                "offset"       => 19,
                "dead"         => "O",
                "child_color"  => "P",
            ],
            self::SECOND_PAGE => [
                "offset"               => 6,
                "livestock_specialist" => "X20",
                "foreman"              => "X22",
                "date"                 => [
                    "day"   => "P24",
                    "month" => "R24",
                    "year"  => "AA24",
                ],
                "dead"                 => "Q",
                "child_color"          => "U",
            ],
        ],
        self::SECOND_REPORT => [
            self::FIRST_PAGE  => [
                "date"         => [
                    "day"   => "S35",
                    "month" => "T35",
                    "year"  => "U35",
                ],
                "organization" => "D36",
                "department"   => "C37",
                "foreman"      => "G40",
                "offset"       => 46,
                "dead"         => "O",
                "child_color"  => "P",
            ],
            self::SECOND_PAGE => [
                "offset"               => 33,
                "livestock_specialist" => "X47",
                "foreman"              => "X49",
                "date"                 => [
                    "day"   => "P51",
                    "month" => "R51",
                    "year"  => "AA51",
                ],
                "dead"                 => "Q",
                "child_color"          => "U",
            ],
        ],
    ];

    /**
     * ReportExcelPostingOffspring constructor.
     * @param $model
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct($model)
    {
        parent::__construct(
            self::REPORT_TEMPLATE_NAME,
            self::REPORT_DIRECTORY_REPORTS,
            self::REPORT_TEMPLATE_FILE_NAME
        );

        $this->init($model);
        $this->fetchData();
        $this->prepareData();
    }

    /**
     * @param $field
     * @return mixed
     */
    private function getFieldName($field)
    {
        return ArrayHelper::getValue(
            $this->fields,
            "$this->curReport.$this->curSheetIndex.$field"
        );
    }

    /**
     * @param $type
     * @return string
     */
    private function getOrganizationName($type)
    {
        switch ($type) {
            case 0:
                return 'ООО "Агро-Нептун"';
            case 1:
                return "КФХ Калганов";
            default:
                return "";
        }
    }

    /**
     * @param $model
     */
    private function init($model)
    {
        $this->organization = $this->getOrganizationName(
            ArrayHelper::getValue($model, "organization_type")
        );

        $this->department = ArrayHelper::getValue($model, "department");

        $foremanId = ArrayHelper::getValue($model, "user_id");
        $foreman = User::findOne($foremanId);
        $this->foreman = DataHelper::getFioWithInitials(
            ArrayHelper::getValue($foreman, "firstName"),
            ArrayHelper::getValue($foreman, "lastName"),
            ArrayHelper::getValue($foreman, "middleName")
        );

        $livestockSpecialistId = ArrayHelper::getValue($model, "livestock_specialist_id");
        $livestockSpecialist = User::findOne($livestockSpecialistId);
        $this->livestockSpecialist = DataHelper::getFioWithInitials(
            ArrayHelper::getValue($livestockSpecialist, "firstName"),
            ArrayHelper::getValue($livestockSpecialist, "lastName"),
            ArrayHelper::getValue($livestockSpecialist, "middleName")
        );

        $this->dateFrom = ArrayHelper::getValue($model, "dateFrom");
        $this->dateTo = ArrayHelper::getValue($model, "dateTo");
    }

    /**
     * Группировка по дате
     */
    private function groupByDate()
    {
        if (!$this->data) {
            return;
        }

        $this->data = ArrayHelper::map(
            $this->data,
            "calving_id",
            function ($item) {
                return $item;
            },
            "calving_date"
        );
    }

    /**
     * Разбивка по файлам
     */
    private function partition()
    {
        foreach ($this->data as $date => $animals) {
            $dataForReport = array_chunk($animals, $this->maxCountInOneReport);
            foreach ($dataForReport as $reportData) {
                $this->dataReports[] = [
                    "date" => $date,
                    "data" => $reportData
                ];
            }
        }

        if (count($this->dataReports) > 1) {
            $this->needToArchive = true;
        }
    }

    /**
     * Преобразование данных
     */
    private function prepareData()
    {
        $this->groupByDate();
        $this->partition();
    }

    /**
     * Получение данных для генерации отчета
     */
    public function fetchData()
    {
        $this->data = Offspring::getCalvingList($this->dateFrom, $this->dateTo);
    }

    /**
     * @param $date
     * @throws Exception
     */
    private function fillHead($date)
    {
        $this->switchActiveSheet(0);

        $this->activeSheet()->setCellValue(
            $this->getFieldName("organization"),
            $this->organization
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("department"),
            $this->department
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("foreman"),
            $this->foreman
        );

        $date = new DateTime($date, new DateTimeZone('Europe/Samara'));
        $this->activeSheet()->setCellValue(
            $this->getFieldName("date.day"),
            $date->format("d")
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("date.month"),
            $date->format("m")
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("date.year"),
            $date->format("y")
        );
    }

    /**
     * @param $rowData
     * @param $offset
     * @throws Exception
     */
    private function fillRow($rowData, &$offset)
    {
        $this->activeSheet()->setCellValue(
            "A$offset",
            ArrayHelper::getValue($rowData, "child_group_name")
        );

        $this->activeSheet()->setCellValue(
            "E$offset",
            ArrayHelper::getValue($rowData, "mother_label")
        );

        if (ArrayHelper::getValue($rowData, "child_health_status") != Animal::HEALTH_STATUS_DEAD) {
            $sex = ArrayHelper::getValue($rowData, "sex");
            $this->activeSheet()->setCellValue(
                "H$offset",
                $sex == Animal::SEX_TYPE_MAN ? 1 : "-"
            );

            $weight = ArrayHelper::getValue($rowData, "birth_weight");
            $this->activeSheet()->setCellValue(
                "I$offset",
                $sex == Animal::SEX_TYPE_MAN ? $weight : "-"
            );

            $this->activeSheet()->setCellValue(
                "K$offset",
                $sex == Animal::SEX_TYPE_WOMAN ? 1 : "-"
            );

            $this->activeSheet()->setCellValue(
                "M$offset",
                $sex == Animal::SEX_TYPE_WOMAN ? $weight : "-"
            );
        }


        if (ArrayHelper::getValue($rowData, "child_health_status") != Animal::HEALTH_STATUS_DEAD) {
            $this->activeSheet()->setCellValue(
                "N$offset",
                ArrayHelper::getValue($rowData, "child_label")
            );
        }

        if (ArrayHelper::getValue($rowData, "child_health_status") == Animal::HEALTH_STATUS_DEAD) {
            $this->activeSheet()->setCellValue(
                $this->getFieldName("dead") . $offset,
                1
            );
        }

        $this->activeSheet()->setCellValue(
            $this->getFieldName("child_color") . $offset,
            ArrayHelper::getValue($rowData, "child_color_shortname")
        );

        $offset++;
    }

    /**
     * @param $data
     * @throws Exception
     */
    private function fillMain($data)
    {
        $offset = $this->getFieldName("offset");

        if (count($data) <= 7) {
            for ($i = 0; $i < count($data); $i++) {
                $this->fillRow($data[$i], $offset);
            }
        }

        if (count($data) > 7) {
            for ($i = 0; $i < 7; $i++) {
                $this->fillRow($data[$i], $offset);
            }

            $this->switchActiveSheet(1);
            $offset = $this->getFieldName("offset");
            for ($i = 7; $i < count($data); $i++) {
                $this->fillRow($data[$i], $offset);
            }
        }
    }

    /**
     * @param $date
     * @throws Exception
     */
    private function fillFooter($date)
    {
        $this->switchActiveSheet(1);

        $this->activeSheet()->setCellValue(
            $this->getFieldName("livestock_specialist"),
            $this->livestockSpecialist
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("foreman"),
            $this->foreman
        );

        $date = new DateTime($date, new DateTimeZone('Europe/Samara'));
        $this->activeSheet()->setCellValue(
            $this->getFieldName("date.day"),
            $date->format("d")
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("date.month"),
            DateHelper::getMonthName($date->format("n"))
        );

        $this->activeSheet()->setCellValue(
            $this->getFieldName("date.year"),
            $date->format("y")
        );
    }

    /**
     *
     */
    private function changeReport()
    {
        $this->curReport ^= 1;
    }

    /**
     * @param $data
     * @throws Exception
     */
    private function fillReport($data)
    {
        $this->fillHead($data['date']);
        $this->fillMain($data['data']);
        $this->fillFooter($data['date']);
    }

    /**
     * Необходимость перезагрузить шаблон
     *
     * @return bool
     */
    private function needReloadTemplate()
    {
        return $this->curSheetIndex && $this->curReport;
    }

    /**
     * @return mixed|void
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generate()
    {
        foreach ($this->dataReports as $index => $dataReport) {
            $this->fillReport($dataReport);
            $this->saveFile($index);
            $this->changeReport();
        }

        if ($this->needToArchive) {
            $this->saveInArchive();
        }
    }

    /**
     * @param $index
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function saveFile($index)
    {
        $isLast = count($this->dataReports) - 1 == $index;

        if ($this->needReloadTemplate() || $isLast) {
            $this->addNewFile();
            $this->save();
            $this->loadTemplate(self::REPORT_TEMPLATE_NAME);
        }
    }

}