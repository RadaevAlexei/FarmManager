<?php

namespace common\models;

/**
 * Class Bull
 * @package common\models
 */
class Bull extends Animal
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::tableName();
    }
}