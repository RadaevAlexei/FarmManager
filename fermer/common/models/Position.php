<?php

namespace common\models;

use Yii;
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
        return [
            'name'       => Yii::t('app/position', 'POSITION_NAME'),
            'short_name' => Yii::t('app/position', 'POSITION_SHORT_NAME')
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
     * Получение всех должностей
     *
     * @return array
     */
    public static function getAllPositions()
    {
        $positions = self::find()->select(['name', 'id'])->indexBy('id')->column();
        return $positions;
    }

    /**
     * Получаем массив всех ID-шников существующих должностей
     * @return array
     */
    public static function getAllPositionsIDs()
    {
        $positions = self::find()->select('id')->column();
        return $positions;
    }
}