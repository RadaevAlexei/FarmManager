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
    const TYPE_TEXT_STRING = "text_value";

    // Число
    const TYPE_NUMBER = 1;
    const TYPE_NUMBER_STRING = "number_value";

    // Список
    const TYPE_LIST = 2;
    const TYPE_LIST_STRING = "list_value";

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::TYPE_TEXT   => Yii::t('app/type-field', 'TYPE_FIELD_TEXT'),
            self::TYPE_NUMBER => Yii::t('app/type-field', 'TYPE_FIELD_NUMBER'),
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