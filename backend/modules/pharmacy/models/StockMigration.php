<?php

namespace backend\modules\pharmacy\models;

use Yii;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * Class StockMigration
 * @package backend\modules\pharmacy\models
 *
 * @property \DateTime $date
 * @property integer $user_id
 * @property integer $preparation_id
 * @property integer $stock_from_id
 * @property integer $stock_to_id
 * @property integer $count
 * @property double $volume
 */
class StockMigration extends ActiveRecord
{
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%stock_migration}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'date'           => 'Дата перемещения',
            'user_id'        => 'Кто переместил',
            'preparation_id' => 'Препарат',
            'stock_from_id'  => 'Откуда перемещено',
            'stock_to_id'    => 'Куда перемещено',
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
            [['date', 'user_id', 'stock_from_id', 'stock_to_id', 'count', 'volume'], 'required'],
            [['user_id', 'stock_from_id', 'stock_to_id', 'preparation_id', 'count'], 'integer'],
            [['volume'], 'double'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockFrom()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockTo()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_to_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreparation()
    {
        return $this->hasOne(Preparation::class, ['id' => 'preparation_id']);
    }
}