<?php

namespace backend\models\reports;

use Yii;
use backend\modules\reproduction\models\Insemination;
use common\models\rectal\Rectal;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\helpers\ArrayHelper;

/**
 * Отчет для гинеколога
 *
 * Class ReportExcelGynecologist
 * @package backend\models\reports
 */
class ReportExcelGynecologist extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_rectal_list_for_gynecologist.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "rectal_list_for_gynecologist";
    const REPORT_DIRECTORY_REPORTS = "reports/rectal/rectal_list_for_gynecologist/";

    private $dateFrom;
    private $dateTo;
    private $data;

    /**
     * ReportExcelGynecologist constructor.
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
     * Получение данных, на основе которых будет генерироваться отчет
     */
    public function fetchData()
    {
        $this->data = Rectal::getRectalListForGynecologist($this->dateFrom, $this->dateTo);
    }

    /**
     * @throws Exception
     */
    private function fillHead()
    {
        $this->activeSheet()->setTitle('Список для гинеколога');

        $this->activeSheet()->setCellValue(
            "F1",
            (new DateTime('now', new DateTimeZone('Europe/Samara')))
                ->format('d.m.Y')
        );

        $this->activeSheet()->setCellValue(
            "F2",
            $this->getPeriodText($this->dateFrom, $this->dateTo)
        );
    }

    /**
     * @throws Exception
     */
    private function fillMain()
    {
        $count = count($this->data);
        if ($count > 1) {
            $this->activeSheet()->insertNewRowBefore(5, $count - 1);
        }

        $offset = 4;
        $index = 1;
        foreach ($this->data as $ri) {
            $this->activeSheet()->setCellValue("A$offset", $index);
            $this->activeSheet()->setCellValue(
                "B$offset",
                ArrayHelper::getValue($ri, "animal_group_name")
            );
            $this->activeSheet()->setCellValue(
                "C$offset",
                ArrayHelper::getValue($ri, "collar")
            );
            $this->activeSheet()->setCellValue(
                "D$offset",
                ArrayHelper::getValue($ri, "label")
            );
            $this->activeSheet()->setCellValue(
                "E$offset",
                ArrayHelper::getValue($ri, "count_insemination")
            );
            $this->activeSheet()->setCellValue(
                "F$offset",
                Insemination::getTypeInseminationLabel(
                    ArrayHelper::getValue($ri, "type_insemination")
                )
            );
            $days = ArrayHelper::getValue($ri, "days");
            $this->activeSheet()->setCellValue("H$offset", $days ? $days : 0);
            $index++;
            $offset++;
        }
    }

    /**
     * Генерирование отчета
     *
     * @return mixed|void
     * @throws Exception
     */
    public function generate()
    {
        $this->fillHead();
        $this->fillMain();
        $this->addNewFile();
    }

}