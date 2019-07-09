<?php

namespace common\models;

use Yii;

/**
 * Class ContractorSeed
 * @package common\models
 */
class ContractorSeed
{
    // Поставщик 1
    const CONTRACTOR_1 = 1;
    const CONTRACTOR_2 = 2;
    const CONTRACTOR_3 = 3;
    const CONTRACTOR_4 = 4;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::CONTRACTOR_1   => 'Поставщик семени 1',
            self::CONTRACTOR_2   => 'Поставщик семени 2',
            self::CONTRACTOR_3   => 'Поставщик семени 3',
            self::CONTRACTOR_4   => 'Поставщик семени 4',
        ];
    }

    /**
     * @param $contractor
     * @return mixed
     */
    public static function getName($contractor)
    {
        return self::getList()[$contractor];
    }
}