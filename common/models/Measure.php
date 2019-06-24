<?php

namespace common\models;

/**
 * Class Measure
 * @package common\models
 */
class Measure
{
    const TYPE_SYRINGE = 1;
    const TYPE_BOTTLE = 2;
    const TYPE_CANISTER = 3;
    const TYPE_KILOGRAM = 4;
    const TYPE_DOSE = 5;
    const TYPE_LITER = 6;
    const TYPE_PIECE = 7;
    const TYPE_BARREL = 8;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::TYPE_SYRINGE  => 'Шприц',
            self::TYPE_BOTTLE   => 'Флакон',
            self::TYPE_CANISTER => 'Канистра',
            self::TYPE_KILOGRAM => 'Килограмм',
            self::TYPE_DOSE     => 'Доза',
            self::TYPE_LITER    => 'Литр',
            self::TYPE_PIECE    => 'Штука',
            self::TYPE_BARREL   => 'Бочка',
        ];
    }
}