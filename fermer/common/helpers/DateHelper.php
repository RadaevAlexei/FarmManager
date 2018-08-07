<?php

namespace common\helpers;

/**
 * Class DateHelper
 * @package common\helpers
 */
class DateHelper
{
    /**
     * Конвертация из одного формата в другой
     *
     * @param string $from_format
     * @param string $data_str
     * @param string $to_format
     * @return string
     */
    public static function convertFromTo($from_format = "d.m.y", $data_str = "", $to_format = "Y-m-d")
    {
        $date = \DateTime::createFromFormat($from_format, $data_str);
        if (!$date) {
            $date = \DateTime::createFromFormat("d/m/y", $data_str);
        }
        $date_formatted = $date->format($to_format);

        return $date_formatted;
    }
}