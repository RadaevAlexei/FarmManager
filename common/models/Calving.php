<?php

namespace common\models;

use Throwable;
use Yii;
use DateTime;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * Class Calving - отелы
 * @package common\models
 *
 * @property integer $id
 * @property integer $animal_id
 * @property DateTime $date
 * @property integer $status
 * @property integer $position
 * @property string $note
 * @property integer $user_id
 */
class Calving extends ActiveRecord
{
    const STATUS_INDEPENDENTLY = 1;      // Самостоятельно
    const STATUS_EASY = 2;               // Лёгкое родовспоможение
    const STATUS_MEDIUM = 3;             // Роды средней тяжести. Патологические роды
    const STATUS_HEAVY = 4;              // Тяжелые роды. Патологические роды
    const STATUS_CESAREAN_SECTION = 5;   // Кесарево сечение

    const POSITION_BACK = 0;             // Спинное
    const POSITION_BELLY = 1;            // Брюшное

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%calving}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'animal_id' => 'Кто отелился',
            'date' => 'Дата',
            'status' => 'Лёгкость отёла',
            'position' => 'Предлежание плода',
            'note' => 'Примечание',
            'user_id' => 'Кто проводил отёл',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'date', 'status', 'position', 'user_id'], 'required'],
            [['animal_id', 'status', 'position', 'user_id'], 'integer'],
            [['note'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * Список всех возможных статусов при родах
     * @return array
     */
    public static function getListStatuses()
    {
        return [
            self::STATUS_INDEPENDENTLY => 'Самостоятельно',
            self::STATUS_EASY => 'Лёгкое родовспоможение',
            self::STATUS_MEDIUM => 'Роды средней тяжести',
            self::STATUS_HEAVY => 'Тяжелые роды',
            self::STATUS_CESAREAN_SECTION => 'Кесарево сечение',
        ];
    }

    /**
     * Получение лейбла статуса при отёле
     * @param $status
     * @return mixed
     */
    public static function getStatusLabel($status)
    {
        return self::getListStatuses()[$status];
    }

    /**
     * Список всех возможных позиций при родах
     * @return array
     */
    public static function getListPositions()
    {
        return [
            self::POSITION_BACK => 'Спинное',
            self::POSITION_BELLY => 'Брюшное'
        ];
    }

    /**
     * Получение лейбла позиции при отёле
     * @param $position
     * @return mixed
     */
    public static function getPositionLabel($position)
    {
        return self::getListPositions()[$position];
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
     * Получение приплода отёла
     * @return ActiveQuery
     */
    public function getAnimals()
    {
        return $this->hasMany(Animal::class, ['id' => 'child_animal_id'])
            ->viaTable(CalvingLink::tableName(), ['calving_id' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }

    /**
     * Удаление животного из отёла.
     * Если в отеле не остается животных - то удалить отёл
     * И потом уже удаляем животного
     * @param $animalId
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteChildAnimal($animalId)
    {
        CalvingLink::deleteAll([
            "AND",
            ['=', 'child_animal_id', $animalId],
            ['=', 'calving_id', $this->id],
        ]);

        $childAnimals = CalvingLink::find()
            ->andWhere(['=', 'calving_id', $this->id])
            ->exists();

        if (!$childAnimals) {
            $this->delete();
        }

        Animal::findOne($animalId)->delete();
    }

    /**
     * Удаление отёла
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteCalving()
    {
        $deleteAnimalsId = $this->getAnimals()->select('id')->asArray()->column();

        Animal::deleteAll(
            ['in', 'id', $deleteAnimalsId]
        );

        CalvingLink::deleteAll([
            'calving_id' => $this->id
        ]);

        $this->delete();
    }

}