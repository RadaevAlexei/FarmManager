<?php

namespace common\models;

use Yii;

/**
 * ТИП СПИСКА
 *
 * Class TypeList
 * @package common\models
 */
class TypeList
{
    // Не множественный
    const SINGLE = 1;

    // Множественный
    const MULTIPLE = 2;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::SINGLE   => Yii::t('app/type-list', 'TYPE_LIST_SINGLE'),
            self::MULTIPLE => Yii::t('app/type-list', 'TYPE_LIST_MULTIPLE')
        ];
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    public static function getType($type)
    {
        return self::getList()[$type];
    }
}