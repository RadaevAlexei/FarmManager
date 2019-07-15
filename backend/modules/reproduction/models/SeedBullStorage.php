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
            'seed_bull_id'       => 'Бык',
            'container_duara_id' => 'Сосуд Дьюара',
            'count'              => 'Количество',
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
     * @param $seed_bull_id
     * @param $container_duara_id
     * @param $count
     */
    public static function substractSeedBull($seed_bull_id, $container_duara_id, $count)
    {
        /** @var SeedBullStorage $storageFrom */
        $storageFrom = SeedBullStorage::find()
            ->where([
                'seed_bull_id'       => $seed_bull_id,
                'container_duara_id' => $container_duara_id,
            ])
            ->one();

        if ($storageFrom) {
            $storageFrom->updateAttributes([
                'count' => $storageFrom->count - $count
            ]);
        } else {
            $storageFrom = new SeedBullStorage([
                'seed_bull_id'       => $seed_bull_id,
                'container_duara_id' => $container_duara_id,
                'count'              => -$count,
            ]);
            $storageFrom->save();
        }
    }
}