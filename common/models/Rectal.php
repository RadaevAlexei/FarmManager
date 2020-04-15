<?php

namespace common\models;

use Yii;
use Throwable;
use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Rectal - ректальное исследование
 * @package common\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property DateTime $date
 * @property integer $animal_id
 * @property integer $result
 */
class Rectal extends ActiveRecord
{
    const RESULT_NOT_STERILE = 0; // "Не стельная"
    const RESULT_STERILE = 1;     // "Стельная"
    const RESULT_DUBIOUS = 2;     // "Сомнительная"

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%rectal}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Кто проводил',
            'date' => 'Дата',
            'animal_id' => 'Животное',
            'result' => 'Результат',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'date', 'animal_id', 'result'], 'required'],
            [['animal_id', 'result', 'user_id'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * Список всех возможных результатов РИ
     * @return array
     */
    public static function getListResults()
    {
        return [
            self::RESULT_NOT_STERILE => 'Не стельная',
            self::RESULT_STERILE => 'Стельная',
            self::RESULT_DUBIOUS => 'Сомнительная',
        ];
    }

    /**
     * Получение лейбла результата
     * @param $result
     * @return mixed
     */
    public static function getResultLabel($result)
    {
        return self::getListResults()[$result];
    }

    /**
     * @return ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}