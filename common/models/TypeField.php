<?php

namespace common\models;

use Yii;

/**
 * Class TypeField
 * @package common\models
 */
class TypeField
{
    // Текст
    const TYPE_TEXT = 0;

    // Число
    const TYPE_NUMBER = 1;

    // Дата
    const TYPE_DATE = 2;

    // Список
    const TYPE_LIST = 3;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::TYPE_TEXT   => Yii::t('app/type-field', 'TYPE_FIELD_TEXT'),
            self::TYPE_NUMBER => Yii::t('app/type-field', 'TYPE_FIELD_NUMBER'),
            self::TYPE_DATE   => Yii::t('app/type-field', 'TYPE_FIELD_DATE'),
            self::TYPE_LIST   => Yii::t('app/type-field', 'TYPE_FIELD_LIST'),
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