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
use ZipArchive;

/**
 * Class ReportExcel
 * @package backend\models\reports
 */
abstract class ReportExcel
{
    const WRITER_TYPE = "Xlsx";

    /**
     * @var BaseReader
     */
    public $reader;

    /**
     * @var Spreadsheet
     */
    public $spreadsheet;

    /**
     * @var array
     */
    public $newFiles = [];

    /**
     * @var string
     */
    public $resultFilename = "";

    /**
     * @var string
     */
    private $extension = '.xlsx';

    /**
     * @var
     */
    private $directoryReports;

    /**
     * @var
     */
    private $templateFileName;

    /**
     * @var int
     */
    public $curSheetIndex = 0;

    /**
     * Нужно ли архивировать
     * @var bool
     */
    public $needToArchive = false;

    /**
     * @var string
     */
    public $archiveExtension = ".zip";

    /**
     * @var int
     */
    private $indexFile = 0;

    /**
     * ReportExcel constructor.
     * @param $templatePath
     * @param $directoryReports
     * @param $templateFileName
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __construct($templatePath, $directoryReports, $templateFileName)
    {
        $this->load($templatePath);

        $this->directoryReports = $directoryReports;
        $this->templateFileName = $templateFileName;
    }

    /**
     * @param $templatePath
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function load($templatePath)
    {
        $this->reader = new Xlsx();
        $this->loadTemplate($templatePath);
    }

    /**
     * @param $templatePath
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function loadTemplate($templatePath)
    {
        $this->spreadsheet = $this->reader->load(
            $this->getFullPath($templatePath)
        );
        $this->switchActiveSheet(0);
    }

    /**
     * @return Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function activeSheet()
    {
        return $this->spreadsheet->getActiveSheet();
    }

    /**
     *
     * @param $index
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function switchActiveSheet($index)
    {
        if ($this->spreadsheet->getActiveSheetIndex() != $index) {
            $this->spreadsheet->setActiveSheetIndex($index);
            $this->curSheetIndex = $index;
        }
    }

    /**
     * @return mixed
     */
    public function getNewFileName()
    {
        return $this->resultFilename;
    }

    /**
     * @param $filename
     */
    private function setNewFileName($filename)
    {
        $this->resultFilename = $filename;
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
     * @throws \Exception
     */
    public function addNewFile()
    {
//        $prefix = $this->getTimePrefix();
//        $prefix = microtime();
        $prefix = $this->indexFile++;
        $this->newFiles[] = $this->directoryReports . $this->templateFileName . '_' . $prefix . $this->extension;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save()
    {
        if (empty($this->newFiles)) {
            return;
        }

        $writer = IOFactory::createWriter($this->spreadsheet, self::WRITER_TYPE);
        $lastFile = end($this->newFiles);
        $writer->save($lastFile);

        $this->setNewFileName($lastFile);
    }

    /**
     *
     */
    private function deleteAllFiles()
    {
        foreach ($this->newFiles as $newFile) {
            if (file_exists($newFile)) {
                unlink($newFile);
            }
        }
    }

    /**
     *
     */
    public function saveInArchive()
    {
        $dateFrom = "01.01.2020";
        $dateTo = "10.06.2020";
        $archiveName = "posting_offspring_($dateFrom-$dateTo)";
        $zipFileName = $this->directoryReports . $archiveName . $this->archiveExtension;

        $zip = new ZipArchive;
        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            foreach ($this->newFiles as $newFile) {
                $zip->addFile($newFile, basename($newFile));
            }
            $zip->close();
            $this->setNewFileName($zipFileName);
        }
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generateAndSave()
    {
        $this->generate();
        $this->save();
    }
}