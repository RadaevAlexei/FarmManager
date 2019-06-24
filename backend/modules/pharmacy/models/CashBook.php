<?php

namespace backend\modules\pharmacy\models;

use Yii;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * Class CashBook
 * @package backend\modules\pharmacy\models
 *
 * @property integer $user_id
 * @property \DateTime $date
 * @property integer $preparation_id
 * @property integer $stock_id
 * @property integer $count
 * @property double $volume
 * @property double $total_price
 * @property double $vat_percent
 * @property integer $type
 */
class CashBook extends ActiveRecord
{
    const PAGE_SIZE = 10;

    const TYPE_KREDIT = 0;
    const TYPE_DEBIT = 1;

    const SCENARIO_CREATE_DEBIT = "create_debit";
    const SCENARIO_CREATE_KREDIT = "create_kredit";

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%cash_book}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'date'           => 'Дата',
            'user_id'        => 'Кто добавил',
            'preparation_id' => 'Препарат',
            'type'           => 'Тип',
            'stock_id'       => 'Склад',
            'count'          => 'Количество',
            'volume'         => 'Объём',
            'total_price'    => 'Сумма итого',
            'vat_percent'    => 'Ставка НДС,%',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'date',
                    'user_id',
                    'type',
                    'preparation_id',
                    'stock_id',
                    'count',
                    'vat_percent',
                ],
                'required'
            ],
            [['preparation_id', 'stock_id', 'count', 'user_id', 'type'], 'integer'],
            [['volume', 'total_price', 'vat_percent'], 'double'],
            [['date'], 'safe'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_KREDIT => 'Расход',
            self::TYPE_DEBIT  => 'Приход'
        ];
    }

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return self::getTypeList()[$this->type];
    }

    /**
     * @return bool
     */
    public function isDebit()
    {
        return $this->type == self::TYPE_DEBIT;
    }

    /**
     * @return bool
     */
    public function isKredit()
    {
        return $this->type == self::TYPE_KREDIT;
    }

    /**
     * @return string
     */
    public function getScenarioName()
    {
        return ($this->type == self::TYPE_DEBIT) ? self::SCENARIO_CREATE_DEBIT : self::SCENARIO_CREATE_KREDIT;
    }
}