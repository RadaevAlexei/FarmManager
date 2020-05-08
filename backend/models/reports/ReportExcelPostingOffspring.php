<?php

namespace backend\models\reports;

use Yii;
use PhpOffice\PhpSpreadsheet\Exception;

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

    private $offset = 0;

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
        $this->data = [];
    }

    /**
     * @throws \Exception
     */
    public function fillHead()
    {
    }

    /**
     * @throws Exception
     */
    public function fillMainTable()
    {

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