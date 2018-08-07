<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Cowshed
 * @package common\models
 *
 * @property string name
 */
class Cowshed extends ActiveRecord
{
    /**
     * Какое количество коровников будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании коровника
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
        return '{{%cowsheds}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/cowshed', 'COWSHED_NAME')
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