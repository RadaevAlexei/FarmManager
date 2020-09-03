<?php

namespace backend\modules\report\models\forms;

use backend\models\reports\ReportExcelSickPie;
use backend\modules\scheme\models\Diagnosis;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Форма генерирования отчетов по заболеваемости
 *
 * Class ReportSickForm
 * @package backend\models\forms
 *
 * @property integer $type
 * @property integer $diagnosis_id
 * @property string $dateFrom
 * @property string $dateTo
 * @property integer $display
 * @property integer $comparativeAnalysis
 * @property string $compareWith
 */
class ReportSickForm extends Model
{
    /**
     * Общий график заболеваемости по всем видам заболеваний
     */
    const DIAGRAM_TYPE_PIE = 1;

    /**
     * График заболеваний за период без сравнительного анализа
     */
    const DIAGRAM_TYPE_WITHOUT_ANALYSIS = 2;

    /**
     * График заболеваний за период со сравнительным анализом
     */
    const DIAGRAM_TYPE_WITH_ANALYSIS = 3;

    /**
     * Выводить и заболевших и выздоровевших
     */
    const DISPLAY_TYPE_BOTH = 1;

    /**
     * Выводить только заболевших
     */
    const DISPLAY_TYPE_SICK = 2;

    /**
     * Выводить только выздоровевших
     */
    const DISPLAY_TYPE_RECOVERED = 3;

    public $type = self::DIAGRAM_TYPE_PIE;
    public $diagnosis_id;
    public $dateFrom;
    public $dateTo;
    public $display = self::DISPLAY_TYPE_BOTH;
    public $comparativeAnalysis = 0;
    public $compareWith;

    private $_fileName = "";

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['type', 'display'], 'required'],
            [['type', 'comparativeAnalysis', 'diagnosis_id', 'display'], 'integer'],
            [['dateFrom', 'dateTo', 'compareWith'], 'string'],
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Тип диаграммы',
            'diagnosis_id' => 'Диагноз',
            'dateFrom' => 'Начало периода',
            'dateTo' => 'Конец периода',
            'comparativeAnalysis' => 'Сравнительный анализ',
            'compareWith' => 'Год сравнения',
            'display' => 'Отображение',
        ];
    }

    /**
     * @return string[]
     */
    public static function getListType()
    {
        return [
            self::DIAGRAM_TYPE_PIE => 'Общий отчет по всем заболеваниям',
            self::DIAGRAM_TYPE_WITHOUT_ANALYSIS => 'Отчет по диагнозу',
            self::DIAGRAM_TYPE_WITH_ANALYSIS => 'Сравнительный отчет по диагнозу',
        ];
    }

    /**
     * @return string[]
     */
    public static function getListDisplay()
    {
        return [
            self::DISPLAY_TYPE_BOTH => 'Заболевшие/Выздоровевшие',
            self::DISPLAY_TYPE_SICK => 'Заболевшие',
            self::DISPLAY_TYPE_RECOVERED => 'Выздоровевшие',
        ];
    }

    /**
     * @return array
     */
    public static function getListDiagnosis()
    {
        return ArrayHelper::map(Diagnosis::getAllList() ?? [], "id", "name");
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generate()
    {
        $report = new ReportExcelSickPie($this->dateFrom, $this->dateTo, $this->display);
        $report->generateAndSave();
        $this->_fileName = $report->getNewFileName();
    }

    /**
     * Получение имени файла сгенерированного отчета
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }
}
