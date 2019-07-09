<?php

namespace common\models;

use Yii;

/**
 * Class Breed
 * @package common\models
 */
class Breed
{
    // Поставщик 1
    const BREED_1 = 1;
    const BREED_2 = 2;
    const BREED_3 = 3;
    const BREED_4 = 4;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::BREED_1   => 'Порода',
            self::BREED_2   => 'Порода',
            self::BREED_3   => 'Порода',
            self::BREED_4   => 'Порода',
        ];
    }

    /**
     * @param $breed
     * @return mixed
     */
    public static function getName($breed)
    {
        return self::getList()[$breed];
    }
}