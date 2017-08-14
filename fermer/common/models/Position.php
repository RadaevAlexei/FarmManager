<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class Position
 * @package common\models
 *
 * @property string $name
 * @property string $short_name
 */
class Position extends ActiveRecord
{
    /**
     * Какое количество должностей будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании должности
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%position}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'name'       => 'Название должности',
            'short_name' => 'Сокращенное название'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'short_name'], 'unique'],
            [['name', 'short_name'], 'trim'],
            [['name', 'short_name'], 'required', 'on' => self::SCENARIO_CREATE_EDIT],
            [['name', 'short_name'], 'string', 'min' => 4, 'max' => 50, 'on' => self::SCENARIO_CREATE_EDIT]
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'name',
                'short_name'
            ],
            self::SCENARIO_FILTER      => [
                'name',
                'short_name'
            ],
        ];
    }

    /**
     * Полу
     * @return array
     */
    public static function getAllPositions()
    {
        $positions = Position::find()->select('id')->asArray()->column();
        return $positions;
    }
}