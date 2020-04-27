<?php

namespace common\models\rectal;

use backend\modules\reproduction\models\Insemination;
use DateInterval;
use Yii;
use Throwable;
use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Class Rectal - ректальное исследование
 * @package common\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property DateTime $date
 * @property integer $animal_id
 * @property integer $result
 * @property integer $rectal_stage
 */
class Rectal extends ActiveRecord
{
    /**
     * Возможные значения поля result
     */
    const RESULT_NOT_RESULT = 0;             // "Без результата"
    const RESULT_STERILE = 1;                // "Стельная"
    const RESULT_NOT_STERILE = 2;            // "Не стельная"
    const RESULT_DUBIOUS = 3;                // "Сомнительная"
    const RESULT_CANCELLED = 4;              // "Отмененное УЗИ"

    /**
     * Этапы ректального исследования
     */
    const STAGE_FIRST = 1;           // Первая ректалка (28й день)
    const STAGE_CONFIRM_FIRST = 2;   // Подтверждение РИ №1 (56й день)
    const STAGE_CONFIRM_SECOND = 3;  // Подтверждение РИ №2 (205й день)

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
            'user_id'      => 'Кто проводил',
            'date'         => 'Дата',
            'animal_id'    => 'Животное',
            'result'       => 'Результат',
            'rectal_stage' => 'Этап РИ',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'date', 'animal_id', 'result', 'rectal_stage'], 'required'],
            [['animal_id', 'result', 'user_id', 'rectal_stage'], 'integer'],
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
            self::RESULT_STERILE     => 'Стельная',
            self::RESULT_DUBIOUS     => 'Сомнительная',
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

    /**
     * Изменение результата
     * @param $newResult
     */
    public function changeResult($newResult)
    {
        $this->updateAttributes(['result' => $newResult]);
    }

    /**
     * Получение следующего этапа
     * @return int
     */
    public function getNextStage()
    {
        return ($this->rectal_stage == self::STAGE_FIRST) ? self::STAGE_CONFIRM_FIRST : self::STAGE_CONFIRM_SECOND;
    }

}