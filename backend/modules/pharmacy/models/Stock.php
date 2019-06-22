<?php

namespace backend\modules\pharmacy\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Stock
 * @package backend\modules\pharmacy\models
 *
 * @property integer $id
 * @property string $name
 */
class Stock extends ActiveRecord
{
    /**
     * Какое количество складов будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%stock}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название склада'
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
     * Получение списка складов
     *
     * @return array|ActiveRecord[]
     */
    public static function getAllList()
    {
        return self::find()->all();
    }
}