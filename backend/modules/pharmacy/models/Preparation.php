<?php

namespace backend\modules\pharmacy\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Preparation
 * @package backend\modules\pharmacy\models
 *
 * @property string $name
 */
class Preparation extends ActiveRecord
{
    /**
     * Какое количество препаратов будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%preparation}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название препарата'
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
}