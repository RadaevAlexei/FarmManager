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
            'name' => 'Название должности'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'unique'],
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 50],
        ];
    }
}