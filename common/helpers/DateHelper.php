<?php

namespace common\helpers;

use DateTime;
use DateTimeZone;

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
        $date = DateTime::createFromFormat($from_format, $data_str);
        if (!$date) {
            $date = DateTime::createFromFormat("d/m/y", $data_str);
        }
        return $date->format($to_format);
    }

    /**
     * Разница между датами в днях
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @return mixed
     */
    public static function diff($dateFrom, $dateTo)
    {
        $result = $dateFrom->diff($dateTo);
        return $result->invert ? $result->days : 0;
    }

    public static function getDate($date)
    {
        return (new DateTime($date, new DateTimeZone('Europe/Samara')))
            ->format('d.m.Y');
    }

    /**
     * @param $number
     * @return mixed
     */
    public static function getMonthName($number)
    {
        $months = [
            "января",
            "февраля",
            "марта",
            "апреля",
            "мая",
            "июня",
            "июля",
            "августа",
            "сентября",
            "октября",
            "ноября",
            "декабря",
        ];

        return $months[$number - 1];
    }

    /**
     * Добавление текущего времени к дате
     * @param $date
     */
    public static function addCurTimeToDate(&$date)
    {
        $now = new DateTime();

        if ($date instanceof DateTime) {
            $date->setTime($now->format('H'), $now->format('i'), $now->format('s'));
            return;
        }

        $date = $now;
    }
}