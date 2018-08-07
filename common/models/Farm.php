<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Farm
 * @package common\models
 *
 * @property string name
 */
class Farm extends ActiveRecord
{
    /**
     * Какое количество ферм будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании фермы
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
        return '{{%farms}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/farm', 'FARM_NAME')
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['name'], 'trim'],
            [['name'], 'required', 'on' => self::SCENARIO_CREATE_EDIT],
            [['name'], 'string', 'on' => self::SCENARIO_CREATE_EDIT]
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'name'
            ],
            self::SCENARIO_FILTER      => [
                'name'
            ],
        ];
    }
}