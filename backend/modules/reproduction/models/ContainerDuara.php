<?php

namespace backend\modules\reproduction\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Container
 * @package backend\modules\reproduction\models
 */
class ContainerDuara extends ActiveRecord
{
    /**
     * Какое количество сосудов будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%container_duara}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название сосуда'
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
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * Получение списка сосудов
     *
     * @return array|ActiveRecord[]
     */
    public static function getAllList()
    {
        return self::find()->all();
    }
}