<?php

namespace common\helpers\Excel;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Class MyReadFilter
 * @package common\helpers\Excel
 */
class WorksheetReadFilter implements IReadFilter
{
    /**
     * @var int
     */
    private $start_row = 3;

    /**
     * @var int|null
     */
    private $end_row;

    /**
     * @var array
     */
    private $columns = ["C", "D", "H", "E", "F", "I", "K", "P", "AR", "W", "Y"];

    /**
     * @param int $startRow
     * @param int|null $endRow
     */
    public function __construct($startRow = 3, $endRow = null)
    {
        $this->start_row = $startRow;
        $this->end_row = $endRow;
    }

    /**
     * @param string $column
     * @param int $row
     * @param string $worksheetName
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row < $this->start_row || ($this->end_row !== null && $row > $this->end_row)) {
            return false;
        }

        return in_array($column, $this->columns);
    }
}