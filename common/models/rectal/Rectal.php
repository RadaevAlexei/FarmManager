<?php

namespace common\models\rectal;

use backend\modules\reproduction\models\Insemination;
use backend\modules\reproduction\models\SeedBull;
use common\models\Animal;
use common\models\AnimalGroup;
use common\models\User;
use DateInterval;
use Yii;
use Throwable;
use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

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
            self::RESULT_NOT_RESULT  => 'Без результата',
            self::RESULT_NOT_STERILE => 'Не стельная',
            self::RESULT_STERILE     => 'Стельная',
            self::RESULT_DUBIOUS     => 'Сомнительная',
            self::RESULT_CANCELLED   => 'Отмененное',
        ];
    }

    /**
     * @return array
     */
    public static function getListStages()
    {
        return [
            self::STAGE_FIRST          => 'Первая ректалка (28-й день)',
            self::STAGE_CONFIRM_FIRST  => 'Подтверждение РИ №1 (56-й день)',
            self::STAGE_CONFIRM_SECOND => 'Подтверждение РИ №2 (205-й день)',
        ];
    }

    /**
     * @return mixed
     */
    public static function getStageLabel($stage)
    {
        return self::getListStages()[$stage];
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
        return ($this->rectal_stage == self::STAGE_FIRST) ?
            self::STAGE_CONFIRM_FIRST : self::STAGE_CONFIRM_SECOND;
    }

    /**
     * @param $inseminationDateFrom
     * @param null $inseminationDateTo
     * @return array|ActiveRecord[]
     */
    public static function getRectalListForGynecologist($inseminationDateFrom = null, $inseminationDateTo = null)
    {
        $query = Rectal::find()
            ->alias('r')
            ->select([
                'r.id',
                'ag.name as animal_group_name',
                'a.collar',
                'a.label',
                'i.date  as insemination_date',
                'i.type_insemination',
                'a.id as animal_id',
                'u.lastName',
                'r.result',
                '(select count(*) from insemination sub_i where sub_i.animal_id = a.id) as count_insemination'
            ])
            ->innerJoin(['a' => Animal::tableName()], 'a.id = r.animal_id')
            ->leftJoin(['ag' => AnimalGroup::tableName()], 'ag.id = a.animal_group_id')
            ->innerJoin(['i' => Insemination::tableName()], 'i.id = a.cur_insemination_id')
            ->innerJoin(['u' => User::tableName()], 'u.id = i.user_id')
            ->where(['in', 'r.result', [
                Rectal::RESULT_NOT_RESULT, Rectal::RESULT_DUBIOUS
            ]]);

        if ($inseminationDateFrom && $inseminationDateTo) {
            $query->andWhere([
                'and',
                ['>=', 'i.date', $inseminationDateFrom],
                ['<=', 'i.date', $inseminationDateTo]
            ]);
        } else if ($inseminationDateFrom) {
            $query->andWhere(['>=', 'i.date', $inseminationDateFrom]);
        } else {
            $query->andWhere(['<=', 'i.date', $inseminationDateTo]);
        }

        $result = $query->asArray()->all();

        if ($result) {
            $result = ArrayHelper::map($result, "id", function ($item) {
                $animal = Animal::findOne(ArrayHelper::getValue($item, "animal_id"));
                $item['days'] = $animal->getCountSterileDays();
                return $item;
            });
        }


        return $result;
    }

    /**
     * @param null $inseminationDateFrom
     * @param null $inseminationDateTo
     * @return array|ActiveRecord[]
     */
    public static function getRectalList($inseminationDateFrom = null, $inseminationDateTo = null)
    {
        $query = Rectal::find()
            ->alias('r')
            ->select([
                'r.id',
                'ag.name as animal_group_name',
                'a.collar',
                'a.label',
                'i.date  as insemination_date',
                'i.count as insemination_count_dose',
                'i.type_insemination',
                'b.nickname as bull_nickname',
                'b.number_1 as bull_number_1',
                'b.number_2 as bull_number_2',
                'b.number_3 as bull_number_3',
                'a.id as animal_id',
                'u_i.lastName as insemination_lastname',
                'u_r.lastName as rectal_lastname',
                'r.date as rectal_date',
                'r.result',
                '(select count(*) from insemination sub_i where sub_i.animal_id = a.id) as count_insemination'
            ])
            ->innerJoin(['a' => Animal::tableName()], 'a.id = r.animal_id')
            ->leftJoin(['ag' => AnimalGroup::tableName()], 'ag.id = a.animal_group_id')
            ->innerJoin(['i' => Insemination::tableName()], 'i.id = a.cur_insemination_id')
            ->innerJoin(['b' => SeedBull::tableName()], 'i.seed_bull_id = b.id')
            ->leftJoin(['u_i' => User::tableName()], 'u_i.id = i.user_id')
            ->leftJoin(['u_r' => User::tableName()], 'u_r.id = r.user_id')
            ->where(['in', 'r.result', [
                Rectal::RESULT_STERILE, Rectal::RESULT_NOT_STERILE
            ]]);

        if ($inseminationDateFrom && $inseminationDateTo) {
            $query->andWhere([
                'and',
                ['>=', 'i.date', $inseminationDateFrom],
                ['<=', 'i.date', $inseminationDateTo]
            ]);
        } else if ($inseminationDateFrom) {
            $query->andWhere(['>=', 'i.date', $inseminationDateFrom]);
        } else {
            $query->andWhere(['<=', 'i.date', $inseminationDateTo]);
        }

        $result = $query->asArray()->all();

        if ($result) {
            $result = ArrayHelper::map($result, "id", function ($item) {
                $animal = Animal::findOne(ArrayHelper::getValue($item, "animal_id"));
                $item['days'] = $animal->getCountSterileDays();
                $item['service_period'] = $animal->getServicePeriod();
                return $item;
            });
        }

        return $result;
    }

}