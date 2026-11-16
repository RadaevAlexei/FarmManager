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
     * @var array
     */
    private $columns = ["C", "D", "H", "E", "F", "I", "K", "P", "AR", "W", "Y"];

    /**
     * @param string $column
     * @param int $row
     * @param string $worksheetName
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->start_row) {
            if (in_array($column, $this->columns)) {
                return true;
            }
        }
        return false;
    }
}