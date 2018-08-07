<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Color
 * @package common\models
 *
 * @property string $name
 * @property string $short_name
 */
class Color extends ActiveRecord
{
    /**
     * Какое количество мастей будем выводить на странице
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
        return [
            'name'       => Yii::t('app/color', 'COLOR_NAME'),
            'short_name' => Yii::t('app/color', 'COLOR_SHORT_NAME')
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
     * Получение всех мастей
     *
     * @return array
     */
    public static function getAllColors()
    {
        $colors = self::find()->select(['name', 'id'])->indexBy('id')->column();
        return $colors;
    }

    /**
     * Получаем массив всех ID-шников существующих мастей
     * @return array
     */
    public static function getAllColorsIDs()
    {
        $colors = Color::find()->select('id')->column();
        return $colors;
    }
}