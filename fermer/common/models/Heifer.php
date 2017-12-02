<?php

namespace common\models;

/**
 * Class Heifer
 * @package common\models
 */
class Heifer extends Animal
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::tableName();
    }
}