<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * КОРОВА
 *
 * Class Cow
 * @package common\models
 */
class Cow extends Animal
{
    /**
     * Пол животного 1 - корова/телка
     */
    const ANIMAL_SEX_TYPE = 2;

    /**
     * Количество голов на странице
     */
    const PAGE_SIZE = 20;

    /**
     * Сценарий при создании и редактировании головы
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return parent::attributeLabels();
    }

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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }
}