<?php

namespace backend\modules\reproduction\models;

use Yii;
use common\models\Animal;
use common\models\User;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Insemination
 * @package backend\modules\reproduction\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property \DateTime $date
 * @property Animal $animal_id
 * @property SeedBull $seed_bull_id
 * @property integer $count
 * @property integer $type_insemination
 * @property string $comment
 * @property integer $container_duara_id
 */
class Insemination extends ActiveRecord
{
    /**
     * Какое количество осеменений будем выводить на странице
     */
    const PAGE_SIZE = 10;

    const TYPE_NATURAL = 1;
    const TYPE_HORMONAL = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%insemination}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_id'            => 'Техник-осеменатор',
            'date'               => 'Дата',
            'animal_id'          => 'Животное',
            'seed_bull_id'       => 'Бык',
            'count'              => 'Количество доз',
            'type_insemination'  => 'Тип осеменения',
            'comment'            => 'Примечание',
            'container_duara_id' => 'Сосуд Дьюара',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                ['user_id', 'date', 'animal_id', 'seed_bull_id', 'count', 'type_insemination', 'container_duara_id'],
                'required'
            ],
            [['comment'], 'string', 'max' => 255],
            [['user_id', 'seed_bull_id', 'count', 'type_insemination', 'animal_id', 'container_duara_id'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * Получение списка всех осеменений
     * @return array|ActiveRecord[]
     */
    public static function getAllList()
    {
        return self::find()->all();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return mixed
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * @return mixed
     */
    public function getSeedBull()
    {
        return $this->hasOne(SeedBull::class, ['id' => 'seed_bull_id']);
    }

    /**
     * @return mixed
     */
    public function getContainerDuara()
    {
        return $this->hasOne(ContainerDuara::class, ['id' => 'container_duara_id']);
    }

    /**
     * @return array
     */
    public static function getTypesInsemination()
    {
        return [
            self::TYPE_NATURAL  => "Естественная охота",
            self::TYPE_HORMONAL => "Гормональная схема синхронизации",
        ];
    }

    /**
     * @return mixed
     */
    public function getTypeInsemination()
    {
        return ArrayHelper::getValue(self::getTypesInsemination(), $this->type_insemination);
    }

}