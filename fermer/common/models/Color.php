<?php

namespace common\models;


use yii\db\ActiveRecord;

class Color extends ActiveRecord
{

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'name' => 'Название масти',
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
            ['name', 'string', 'length' => [4, 30]],
            ['short_name', 'string', 'length' => [1, 15]]
        ];
    }
}