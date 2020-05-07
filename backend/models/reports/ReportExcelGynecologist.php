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
    const REPORT_DIRECTORY_REPORTS = "reports/rectal/rectal-list/rectal_list_for_gynecologist";

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
        parent::__construct(self::getPathTemplateForReport());

        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->fetchData();
    }

    /**
     * Получение полного пути до файла-шаблона,
     * который будет заполняться данными
     * @return string
     */
    private function getPathTemplateForReport()
    {
        $path = '/reports/rectal/rectal-list/templates/' . self::REPORT_TEMPLATE_NAME;
        return $this->getFullPath($path);
    }

    /**
     * Получение данных, на основе которых будет генерироваться отчет
     */
    private function fetchData()
    {
        $this->data = Rectal::getRectalListForGynecologist($this->dateFrom, $this->dateTo);
    }

    /**
     * Генерирование отчета
     *
     * @throws Exception
     */
    public function generate()
    {
        $this->sheet->setTitle('Список для гинеколога');

        $this->sheet->setCellValue("F1",
            (new DateTime('now', new DateTimeZone('Europe/Samara')))
                ->format('d.m.Y')
        );

        $this->sheet->setCellValue("F2", $this->getPeriodText($this->dateFrom, $this->dateTo));

        $count = count($this->data);
        if ($count > 1) {
            $this->sheet->insertNewRowBefore(5, $count - 1);
        }

        $offset = 4;
        $index = 1;
        foreach ($this->data as $ri) {
            $this->sheet->setCellValue("A$offset", $index);
            $this->sheet->setCellValue("B$offset", ArrayHelper::getValue($ri, "animal_group_name"));
            $this->sheet->setCellValue("C$offset", ArrayHelper::getValue($ri, "collar"));
            $this->sheet->setCellValue("D$offset", ArrayHelper::getValue($ri, "label"));
            $this->sheet->setCellValue("E$offset", ArrayHelper::getValue($ri, "count_insemination"));
            $this->sheet->setCellValue(
                "F$offset",
                Insemination::getTypeInseminationLabel(ArrayHelper::getValue($ri, "type_insemination"))
            );
            $days = ArrayHelper::getValue($ri, "days");
            $this->sheet->setCellValue("H$offset", $days ? $days : 0);
            $index++;
            $offset++;
        }
    }

    /**
     * Сохранение отчета
     * @throws Exception
     */
    public function saveReport()
    {
        self::save(
            self::REPORT_DIRECTORY_REPORTS,
            self::REPORT_TEMPLATE_FILE_NAME
        );
    }

    /**
     * Генерирование и сохранение отчета
     *
     * @throws Exception
     */
    public function generateAndSave()
    {
        $this->generate();
        $this->saveReport();
    }
}