<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Color
 * @package common\models
 *
 * @property string @name
 * @property string @short_name
 */
class Color extends ActiveRecord
{
    /**
     * Количество мастей на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании масти
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
        return '{{%color}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'name'       => 'Название масти',
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
            [['name', 'short_name'], 'string'],
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}