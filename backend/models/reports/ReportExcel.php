<?php

namespace backend\models\reports;

use Yii;
use common\helpers\DateHelper;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class ReportExcel
 * @package backend\models\reports
 */
abstract class ReportExcel
{
    const WRITER_TYPE = "Xlsx";

    /**
     * @var Worksheet
     */
    public $sheet;

    /**
     * @var BaseReader
     */
    public $reader;

    /**
     * @var Spreadsheet
     */
    public $spreadsheet;

    /**
     * @var
     */
    public $newFileName;

    /**
     * ReportExcel constructor.
     * @param $templatePath
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __construct($templatePath)
    {
        $this->load($this->getFullPath($templatePath));
    }

    /**
     * @param $templatePath
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function load($templatePath)
    {
        $this->reader = new Xlsx();
        $this->spreadsheet = $this->reader->load($templatePath);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * @return mixed
     */
    public function getNewFileName()
    {
        return $this->newFileName;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getFullPath($path)
    {
        return Yii::getAlias('@webroot') . '/reports/templates/' . $path;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getTimePrefix()
    {
        return (new DateTime('now', new DateTimeZone('Europe/Samara')))
            ->format('Y_m_d_H_i_s');
    }

    /**
     * @param $destinationFolder
     * @param $fileName
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save($destinationFolder, $fileName)
    {
        $writer = IOFactory::createWriter($this->spreadsheet, self::WRITER_TYPE);
        $extension = '.xlsx';
        $prefix = $this->getTimePrefix();
        $this->newFileName = $destinationFolder . "/" . $fileName . '_' . $prefix . $extension;
        $writer->save($this->newFileName);
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return string
     */
    public function getPeriodText($dateFrom = null, $dateTo = null)
    {
        if ($dateFrom && $dateTo) {
            $period = implode(" - ", [
                DateHelper::getDate($dateFrom),
                DateHelper::getDate($dateTo)
            ]);
        } else if ($dateFrom) {
            $period = "с " . DateHelper::getDate($dateFrom);
        } else {
            $period = "по " . DateHelper::getDate($dateTo);
        }

        return $period;
    }

    /**
     * Получение данных, на основе которых будет генерироваться отчет
     * @return mixed
     */
    abstract public function fetchData();

    /**
     * Генерация отчета
     * @return mixed
     */
    abstract public function generate();

    /**
     * Генерация и сохранение
     * @return mixed
     */
    abstract public function generateAndSave();
}