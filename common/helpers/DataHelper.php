<?php

namespace common\helpers;

use yii\helpers\ArrayHelper;

class DataHelper
{
    /**
     * Склеивание данных массива через разделитель
     * @param $array - Массив для склейки
     * @param $delimiter - Разделитель
     * @return string - в результате получаем строку склеенную из элементов массива $array через разделитель $delimiter
     */
    public static function concatArrayIsNotEmptyElement($array, $delimiter)
    {
        if (empty($array)) {
            return "";
        }

        return implode($delimiter,
            array_values(
                array_filter($array, function ($element) {
                    return !empty($element);
                })
            )
        );
    }

    /**
     * Получение фамилии с инициалами
     * @param null $firstName
     * @param null $lastName
     * @param null $middleName
     * @return string
     */
    public static function getFioWithInitials($firstName = null, $lastName = null, $middleName = null)
    {
        return self::concatArrayIsNotEmptyElement(
            [
                $lastName,
                ucfirst(mb_substr($firstName, 0, 1, "utf-8")) . ".",
                ucfirst(mb_substr($middleName, 0, 1, "utf-8")) . "."
            ], " "
        );
    }

    /**
     * Получение данных в массиве $array, по индексу $searchIndex и ключу $field
     * @param array $array
     * @param $searchIndex
     * @param $field
     * @return mixed|null
     */
    public static function getElementByIndex($array = [], $searchIndex, $field)
    {
        if (empty($array) || ($searchIndex < 0)) {
            return null;
        }

        $curIndex = 0;
        foreach ($array as $item) {
            if ($curIndex == $searchIndex) {
                return ArrayHelper::getValue($item, $field);
            }
            $curIndex++;
        }

        return null;
    }

    /**
     * @param $array
     * @param $searchField
     * @param $searchValue
     * @param $returnValue
     *
     * @return array|mixed|null
     */
    public static function getField($array, $searchField, $searchValue, $returnValue)
    {
        if (empty($array)) {
            return null;
        }

        foreach ($array as $item) {
            $curValue = ArrayHelper::getValue($item, $searchField);
            if ($curValue == $searchValue) {
                if (is_array($returnValue)) {
                    $return = [];
                    foreach ($returnValue as $index) {
                        $return[$index] = ArrayHelper::getValue($item, $index);
                    }
                    return $return;
                }
                return ArrayHelper::getValue($item, $returnValue);
            }
        }

        return null;
    }

    /**
     * Поиск элемента в массиве $array по полю $fieldSearch
     * @param $array
     * @param $fieldSearch
     * @param null $field
     * @return bool
     */
    public static function searchItem($array, $fieldSearch, $field)
    {
        if (empty($array)) {
            return false;
        }

        $searchArray = array_filter($array, function ($object) use ($fieldSearch, $field) {
            if (is_array($object)) {
                return (ArrayHelper::getValue($object, $fieldSearch) == $field);
            }
            return ($object == $field) ? true : false;
        });

        return !empty($searchArray) ? true : false;
    }

    /**
     * @param null $timestamp
     * @param string $format
     * @return bool|string
     */
    public static function getDate($timestamp = null, $format = "d.m.Y")
    {
        return date($format, $timestamp);
    }

    /**
     * @param null $date
     * @param bool $isReplace
     * Получение timastamp даты
     * @return false|int|null
     */
    public static function getTimeStamp($date = null, $isReplace = false)
    {
        if (empty($date)) {
            return null;
        }

        if ($isReplace) {
            $date = str_replace("/", ".", $date);
        }

        return strtotime($date);
    }

    /**
     * @param null $price
     * @return null|string
     */
    public static function priceFormat($price = null, $currency = null, $decimals = 0, $decPoint = ",", $thousandsSep = " ")
    {
        if (empty($price)) {
            return 0;
        }

        $format = number_format($price, $decimals, $decPoint, $thousandsSep);
        if (!empty($currency)) {
            $format .= $currency;
        }

        return $format;
    }

    /**
     * @param null $timestamp
     * @param int $countDays
     * @param bool|false $isConverted
     * @return bool|int|null|string
     */
    public static function addDays($timestamp = null, $countDays = 0, $isConverted = false)
    {
        $newDate = strtotime("+" . $countDays . " day", $timestamp);
        if ($isConverted) {
            return self::getDate($newDate);
        }
        return $newDate;
    }

    /**
     * Разница между датами в днях
     * @param $date
     * @return mixed
     */
    public static function getInterval($_from = null, $_date)
    {
        $_from = empty($_from) ? time() : $_from;

        $from = date_create(self::getDate($_from));
        $to = date_create(self::getDate($_date));
        $days = date_diff($from, $to)->days;

        if ($days > 0) {
            return $days;
        }
        return 0;
    }

    public static function getArrayString($array)
    {
        if (empty($array)) {
            return "";
        }

        $result = "";

        foreach ($array as $key => $data) {
            if (is_string($data)) {
                $result .= $data . PHP_EOL;
            } else if (is_array($data)) {
                foreach ($data as $sub_data) {
                    $result .= $sub_data . PHP_EOL;
                }
            }
        }

        return $result;
    }

    /**
     * Вычисление среднего арифметического значений массива
     * @param array $arr
     * @return float|int
     */
    public static function getAverage($arr = [])
    {
        if (!count($arr)) {
            return 0;
        }
        $sum = array_sum($arr);
        $count = count($arr);
        return $sum / $count;
    }

}