<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Functions
 * @package common\models
 */
class Functions extends ActiveRecord
{

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'name' => 'Название должности',
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
            [['name', 'short_name'], 'required'],
            [['name', 'short_name'], 'string', 'min' => 4, 'max' => 50]
        ];
    }
}