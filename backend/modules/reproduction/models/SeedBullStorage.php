<?php

namespace backend\modules\reproduction\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class SeedBullStorage
 * @package backend\modules\reproduction\models
 *
 * @property integer $seed_bull_id
 * @property integer $container_duara_id
 * @property integer $count
 */
class SeedBullStorage extends ActiveRecord
{
    /**
     * Какое количество будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%seed_bull_storage}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'seed_bull_id' => 'Бык',
            'container_duara_id' => 'Сосуд Дьюара',
            'count' => 'Количество',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['seed_bull_id', 'container_duara_id', 'count'], 'required'],
            [['seed_bull_id', 'container_duara_id', 'count'], 'integer'],
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
     * @param $preparation_id
     * @param $stock_id
     * @param $volume
     * @param $count
     */
    public static function substractPreparation($seed_bull_id, $container_duara_id, $volume, $count)
    {
        /** @var Storage $storageFrom */
        $storageFrom = Storage::find()
            ->where([
                'preparation_id' => $preparation_id,
                'stock_id' => $stock_id,
                'volume' => $volume,
            ])
            ->one();

        if ($storageFrom) {
            $storageFrom->updateAttributes([
                'count' => $storageFrom->count - $count
            ]);
        } else {
            $storageFrom = new Storage([
                'preparation_id' => $preparation_id,
                'stock_id' => $stock_id,
                'count' => -$count,
                'volume' => $volume
            ]);
            $storageFrom->save();
        }
    }
}