<?php

namespace backend\models\reports;

use backend\modules\report\models\forms\ReportSickForm;
use backend\modules\scheme\models\AppropriationScheme;
use Yii;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\Scheme;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Exception;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class ReportExcelSickPie
 * @package backend\models\reports
 */
class ReportExcelSickPie extends ReportExcel
{
    const REPORT_TEMPLATE_NAME = "template_count_by_diagnosis_pie.xlsx";
    const REPORT_TEMPLATE_FILE_NAME = "template_count_by_diagnosis_pie";
    const REPORT_DIRECTORY_REPORTS = "reports/animal/animal_count_by_diagnosis/";

    private $_data;
    /** @var DateTime */
    private $_dateFrom;
    /** @var DateTime */
    private $_dateTo;
    private $_display;
    private $offset = 0;

    /**
     * ReportExcelSickPie constructor.
     * @param $dateFrom
     * @param $dateTo
     * @param $display
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function __construct($dateFrom, $dateTo, $display)
    {
        parent::__construct(
            self::REPORT_TEMPLATE_NAME,
            self::REPORT_DIRECTORY_REPORTS,
            self::REPORT_TEMPLATE_FILE_NAME
        );

        $this->init($dateFrom, $dateTo, $display);
        $this->fetchData();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $display
     * @throws \Exception
     */
    private function init($dateFrom, $dateTo, $display)
    {
        if (!empty($dateFrom)) {
            $this->_dateFrom = new DateTime($dateFrom, new DateTimeZone('Europe/Samara'));
        }
        if (!empty($dateTo)) {
            $this->_dateTo = new DateTime($dateTo, new DateTimeZone('Europe/Samara'));
        }

        $this->_display = $display;
    }

    private function fetchDataBoth()
    {
echo '<pre>';
print_r(111);
echo '</pre>';
die();
    }

    private function fetchDataSick()
    {
        $query = AppropriationScheme::find()
            ->alias('a')
            ->select([
                'd.id',
                'd.name',
                'count(d.id) as count_diagnosis',
            ])
            ->leftJoin('scheme s', 'a.scheme_id = s.id')
            ->leftJoin('diagnosis d', 's.diagnosis_id = d.id')
            ->where(['a.status' => AppropriationScheme::RESULT_STATUS_HEALTHY])
            ->groupBy(['d.id']);

        $dateFromTime = $this->_dateFrom ? $this->_dateFrom->getTimestamp() : null;
        $dateToTime = $this->_dateTo ? $this->_dateTo->getTimestamp() : null;

        if ($this->_dateFrom && $this->_dateTo) {
            $query->andWhere([
                'and',
                ['>=', 'a.finished_at', $dateFromTime],
                ['<=', 'a.finished_at', $dateToTime],
            ]);
        } else if ($this->_dateFrom && !$this->_dateTo) {
            $query->andWhere(['>=', 'a.finished_at', $dateFromTime]);
        } else if (!$this->_dateFrom && $this->_dateTo) {
            $query->andWhere(['<=', 'a.finished_at', $dateToTime]);
        }

        $this->_data = $query->asArray()->all();
    }

    /**
     * Получение данных по количеству выздоровевших
     */
    private function fetchDataRecovered()
    {
        $query = AppropriationScheme::find()
            ->alias('a')
            ->select([
                'd.id',
                'd.name',
                'count(d.id) as count_diagnosis',
            ])
            ->leftJoin('scheme s', 'a.scheme_id = s.id')
            ->leftJoin('diagnosis d', 's.diagnosis_id = d.id')
            ->where(['a.status' => AppropriationScheme::RESULT_STATUS_HEALTHY])
            ->groupBy(['d.id']);

        $dateFromTime = $this->_dateFrom ? $this->_dateFrom->getTimestamp() : null;
        $dateToTime = $this->_dateTo ? $this->_dateTo->getTimestamp() : null;

        if ($this->_dateFrom && $this->_dateTo) {
            $query->andWhere([
                'and',
                ['>=', 'a.finished_at', $dateFromTime],
                ['<=', 'a.finished_at', $dateToTime],
            ]);
        } else if ($this->_dateFrom && !$this->_dateTo) {
            $query->andWhere(['>=', 'a.finished_at', $dateFromTime]);
        } else if (!$this->_dateFrom && $this->_dateTo) {
            $query->andWhere(['<=', 'a.finished_at', $dateToTime]);
        }

        $this->_data = $query->asArray()->all();
    }

    /**
     * Получение данных для генерации отчета
     */
    public function fetchData()
    {
        switch ($this->_display) {
            case ReportSickForm::DISPLAY_TYPE_BOTH:
                $this->fetchDataBoth();
                break;
            case ReportSickForm::DISPLAY_TYPE_SICK:
                $this->fetchDataSick();
                break;
            case ReportSickForm::DISPLAY_TYPE_RECOVERED:
                $this->fetchDataRecovered();
                break;
        }
    }

    /**
     * @throws \Exception
     */
    public function fillHead()
    {
        if ($this->_dateFrom) {
            $this->activeSheet()->setCellValue(
                "F2", $this->_dateFrom->format('d.m.Y')
            );
        }
        if ($this->_dateTo) {
            $this->activeSheet()->setCellValue(
                "T2", $this->_dateTo->format('d.m.Y')
            );
        }
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
        $this->addNewFile();
    }
}
