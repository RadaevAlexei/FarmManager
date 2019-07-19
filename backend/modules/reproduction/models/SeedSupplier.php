<?php

namespace backend\modules\reproduction\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class SeedSupplier
 * @package backend\modules\reproduction\models
 *
 * @property integer $id
 * @property string $name
 */
class SeedSupplier extends ActiveRecord
{
    /**
     * Какое количество поставщиков будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%seed_supplier}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название поставщика'
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
     * Получение списка поставщиков
     *
     * @return array|ActiveRecord[]
     */
    public static function getAllList()
    {
        return self::find()->all();
    }
}