<?php

namespace backend\modules\reproduction\models;

use Yii;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * Class SeedCashBook
 * @package backend\modules\reproduction\models
 *
 * @property integer $user_id
 * @property \DateTime $date
 * @property integer $seed_bull_id
 * @property integer $container_duara_id
 * @property integer $count
 * @property double $total_price_without_vat
 * @property double $total_price_with_vat
 * @property double $vat_percent
 * @property integer $type
 */
class SeedCashBook extends ActiveRecord
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
        return '{{%seed_cash_book}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Дата',
            'user_id' => 'Кто добавил',
            'seed_bull_id' => 'Бык',
            'container_duara_id' => 'Сосуд',
            'count' => 'Количество',
            'total_price_without_vat' => 'Сумма итого, без НДС',
            'total_price_with_vat' => 'Сумма итого, с НДС',
            'vat_percent' => 'Ставка НДС,%',
            'type' => 'Тип',
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
                    'seed_bull_id',
                    'container_duara_id',
                    'count',
                    'vat_percent',
                ],
                'required'
            ],
            [['seed_bull_id', 'container_duara_id', 'count', 'user_id', 'type'], 'integer'],
            [['total_price_with_vat', 'total_price_without_vat', 'vat_percent'], 'double'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeedBull()
    {
        return $this->hasOne(SeedBull::class, ['id' => 'seed_bull_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainerDuara()
    {
        return $this->hasOne(ContainerDuara::class, ['id' => 'container_duara_id']);
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
            self::TYPE_DEBIT => 'Приход'
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
}