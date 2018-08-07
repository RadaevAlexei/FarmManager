<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Перевеска
 *
 * Class Suspension
 * @package common\models
 *
 * @property \DateTime $date
 * @property $animal integer
 * @property $weight double
 */
class Suspension extends ActiveRecord
{
    /**
     * Количество перевесок на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании перевески
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
        return [
            'date'   => 'Дата взвешивания',
            'animal' => 'Животное',
            'weight' => 'Вес',
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%suspensions}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal', 'weight'], 'trim'],
            [['animal', 'weight', 'date'], 'required', 'on' => self::SCENARIO_CREATE_EDIT],
            ['weight', 'double', 'on' => self::SCENARIO_CREATE_EDIT],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalInfo()
    {
        return $this->hasOne(Animal::className(), ['id' => 'animal']);
    }
}