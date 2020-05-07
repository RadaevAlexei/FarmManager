<?php

namespace backend\modules\reproduction\models;

use common\models\rectal\InseminationRectalLink;
use common\models\rectal\Rectal;
use common\models\rectal\RectalSettings;
use DateInterval;
use DateTime;
use Yii;
use common\models\Animal;
use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class Insemination
 * @package backend\modules\reproduction\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property DateTime $date
 * @property Animal $animal_id
 * @property SeedBull $seed_bull_id
 * @property integer $count
 * @property integer $type_insemination
 * @property string $comment
 * @property integer $container_duara_id
 * @property integer $status
 */
class Insemination extends ActiveRecord
{
    /**
     * Какое количество осеменений будем выводить на странице
     */
    const PAGE_SIZE = 10;

    const TYPE_NATURAL = 1;
    const TYPE_HORMONAL = 2;

    const STATUS_NEUTRAL = 0;      // Нейтральный статус
    const STATUS_SEMINAL = 1;      // Плодотворное осеменение
    const STATUS_NOT_SEMINAL = 2;  // Не плодотворное осеменение
    const STATUS_REHEAT = 3;       // Перегул

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
            'id'                 => 'Код',
            'user_id'            => 'Техник-осеменатор',
            'date'               => 'Дата',
            'animal_id'          => 'Животное',
            'seed_bull_id'       => 'Бык',
            'count'              => 'Количество доз',
            'type_insemination'  => 'Тип осеменения',
            'comment'            => 'Примечание',
            'container_duara_id' => 'Сосуд Дьюара',
            'status'             => 'Статус',
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
            [['user_id', 'seed_bull_id', 'count', 'type_insemination', 'animal_id', 'container_duara_id', 'status'], 'integer'],
            [['date'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_NEUTRAL],
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

    /**
     * Список всех возможных статусов
     * @return array
     */
    public static function getListStatuses()
    {
        return [
            self::STATUS_NEUTRAL     => "Нейтральный",
            self::STATUS_SEMINAL     => "Плодотворное",
            self::STATUS_NOT_SEMINAL => "Не плодотворное",
            self::STATUS_REHEAT      => "Перегул",
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return self::getListStatuses()[$this->status];
    }

    /**
     * @throws Exception
     */
    public function createFirst()
    {
        // Определяем дату первой ректалки
        $rectalDate = RectalSettings::calculateRectalDate($this->date, Rectal::STAGE_FIRST);

        $firstRectal = new Rectal([
            'user_id'      => $this->user_id,
            'date'         => $rectalDate,
            'animal_id'    => $this->animal_id,
            'result'       => Rectal::RESULT_NOT_RESULT,
            'rectal_stage' => Rectal::STAGE_FIRST,
        ]);

        if (!$firstRectal->save()) {
            throw new Exception('Ошибка при добавлении осеменения');
        }

        $newInseminationRectalLink = new InseminationRectalLink([
            'animal_id'       => $this->animal_id,
            'insemination_id' => $this->id,
            'rectal_id'       => $firstRectal->id
        ]);

        if (!$newInseminationRectalLink->save()) {
            throw new Exception('Ошибка при добавлении осеменения');
        }

        // Текущее осеменение
        Animal::findOne($this->animal_id)->changeCurInsemination($this->id);
    }

    /**
     * Изменение статуса
     * @param $newStatus
     */
    public function changeStatus($newStatus)
    {
        $this->updateAttributes(['status' => $newStatus]);
    }

    /**
     * @return array|ActiveRecord|null
     */
    public function getCurStage()
    {
        $animal = Animal::findOne($this->animal_id);
        $curInseminationId = ArrayHelper::getValue($animal, "cur_insemination_id");

        return InseminationRectalLink::find()
            ->alias('link')
            ->select([
                'link.id',
                'link.animal_id',
                'link.insemination_id',
                'link.rectal_id',
                'i.date as insemination_date',
                'r.date as rectal_date',
                'r.result',
                'r.rectal_stage'
            ])
            ->leftJoin(['r' => Rectal::tableName()], 'link.rectal_id = r.id')
            ->leftJoin(['i' => Insemination::tableName()], 'link.insemination_id = i.id')
            ->where([
                'and',
                ['=', 'link.animal_id', $this->animal_id],
                ['=', 'link.insemination_id', $curInseminationId]
            ])
            ->orderBy(['link.id' => SORT_DESC])
            ->limit(1)
            ->asArray()
            ->one();
    }

    /**
     * Получение текущего этапа ректального исследования
     *
     * @throws \Exception
     */
    public function createNewInsemination()
    {
        $curStageData = $this->getCurStage();
        $this->createByResultAndStage($curStageData);
        Animal::findOne($this->animal_id)->changeCurInsemination($this->id);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function createByResultAndStage($data)
    {
        $stage = ArrayHelper::getValue($data, "rectal_stage");
        $result = ArrayHelper::getValue($data, "result");

        if ($stage == Rectal::STAGE_FIRST && $result == Rectal::RESULT_NOT_RESULT) {
            // Ставим перегул
            $curInsemination = Insemination::findOne(ArrayHelper::getValue($data, "insemination_id"));
            $curInsemination->changeStatus(self::STATUS_REHEAT);

            // помечаем планируемое РИ как отмененное
            $rectal = Rectal::findOne(ArrayHelper::getValue($data, "rectal_id"));
            $rectal->changeResult(Rectal::RESULT_CANCELLED);

            // переносим дату первой ректалки на дату $rectalDate
            $rectalDate = RectalSettings::calculateRectalDate($this->date, Rectal::STAGE_FIRST);

            $firstRectal = new Rectal([
                'user_id'      => $this->user_id,
                'date'         => $rectalDate,
                'animal_id'    => $this->animal_id,
                'result'       => Rectal::RESULT_NOT_RESULT,
                'rectal_stage' => Rectal::STAGE_FIRST,
            ]);

            $firstRectal->save();

            $newInseminationRectalLink = new InseminationRectalLink([
                'prev_id'         => ArrayHelper::getValue($data, 'id'),
                'animal_id'       => $this->animal_id,
                'insemination_id' => $this->id,
                'rectal_id'       => $firstRectal->id
            ]);

            $newInseminationRectalLink->save();
        } else if ($stage == Rectal::STAGE_FIRST && $result == Rectal::RESULT_DUBIOUS) {
            // Ставим перегул
            $curInsemination = Insemination::findOne(ArrayHelper::getValue($data, "insemination_id"));
            $curInsemination->changeStatus(self::STATUS_REHEAT);

            // переносим дату первой ректалки на дату $rectalDate
            $nextRectalDate = RectalSettings::calculateRectalDate($this->date, Rectal::STAGE_FIRST);

            $nextRectal = new Rectal([
                'user_id'      => $this->user_id,
                'date'         => $nextRectalDate,
                'animal_id'    => $this->animal_id,
                'result'       => Rectal::RESULT_NOT_RESULT,
                'rectal_stage' => Rectal::STAGE_FIRST,
            ]);

            $nextRectal->save();

            $newInseminationRectalLink = new InseminationRectalLink([
                'prev_id'         => ArrayHelper::getValue($data, 'id'),
                'animal_id'       => $this->animal_id,
                'insemination_id' => $this->id,
                'rectal_id'       => $nextRectal->id
            ]);

            $newInseminationRectalLink->save();
        } else {
            echo '<pre>';
            print_r(9999);
            echo '</pre>';
            die();
        }
    }

    public static function isHormonal($type)
    {
        return ($type == self::TYPE_HORMONAL);
    }

    public static function isNatural($type)
    {
        return ($type == self::TYPE_NATURAL);
    }

    public static function getTypeInseminationLabel($type)
    {
        return self::isNatural($type) ? "ОХОТА" : "Другое";
    }

    /**
     * Получаем список перегулов в диапазоне
     * @param null $dateFrom
     * @param null $dateTo
     * @return mixed
     */
    public static function getReheatsList($dateFrom = null, $dateTo = null)
    {
        $query = Insemination::find()
            ->alias('i')
            ->select([
                'u.id',
                'u.lastName',
                'u.firstName',
                'u.middleName',
            ])
            ->where(['i.status' => self::STATUS_REHEAT])
            ->innerJoin(['u' => User::tableName()], 'u.id = i.user_id')
            ->orderBy(['i.date' => SORT_DESC]);

        if ($dateFrom && $dateTo) {
            $query->andWhere([
                'and',
                ['>=', 'i.date', $dateFrom],
                ['<=', 'i.date', $dateTo]
            ]);
        } else if ($dateFrom) {
            $query->andWhere(['>=', 'i.date', $dateFrom]);
        } else if ($dateTo) {
            $query->andWhere(['<=', 'i.date', $dateTo]);
        }

        return $query->asArray()->all();
    }

}