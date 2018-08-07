<?php

namespace common\models;

/**
 * БЫК
 *
 * Class Bull
 * @package common\models
 */
class Bull extends Animal
{
    /**
     * Пол животного 2 - бык
     */
    const ANIMAL_SEX_TYPE = 1;

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::tableName();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return parent::attributeLabels();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => array_merge(
                [],
                parent::scenarios()
            ),
            self::SCENARIO_FILTER      => array_merge(
                [],
                parent::scenarios()
            )
        ];
    }
}