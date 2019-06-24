<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class AnimalGroup
 * @package common\models
 */
class AnimalGroup extends ActiveRecord
{
    /**
     * Количество групп на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%animal_group}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название'
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
            [['name',], 'required'],
            [['name',], 'string'],
        ];
    }

}