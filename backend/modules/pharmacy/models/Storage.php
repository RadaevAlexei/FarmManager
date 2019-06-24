<?php

namespace backend\modules\pharmacy\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Storage
 * @package backend\modules\pharmacy\models
 *
 * @property integer $preparation_id
 * @property integer $stock_id
 * @property integer $count
 * @property double $volume
 */
class Storage extends ActiveRecord
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
        return '{{%storage}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'preparation_id' => 'Препарат',
            'stock_id'       => 'Склад',
            'count'          => 'Количество',
            'volume'         => 'Объём',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['preparation_id', 'stock_id', 'count', 'volume'], 'required'],
            [['preparation_id', 'stock_id', 'count'], 'integer'],
            [['volume'], 'double']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreparation()
    {
        return $this->hasOne(Preparation::class, ['id' => 'preparation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }
}