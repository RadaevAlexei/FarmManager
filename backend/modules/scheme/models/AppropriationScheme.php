<?php

namespace backend\modules\scheme\models;

use DateTime;
use Exception;
use Throwable;
use Yii;
use common\models\Animal;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class AppropriationScheme
 * @package backend\modules\scheme\models
 *
 * @property integer $id
 * @property integer $animal_id
 * @property integer $scheme_id
 * @property integer $status
 * @property DateTime $started_at
 * @property DateTime $finished_at
 */
class AppropriationScheme extends ActiveRecord
{
    const STATUS_IN_PROGRESS = 0;
    const RESULT_STATUS_HEALTHY = 1;
    const RESULT_STATUS_SICK = 2;
    const RESULT_STATUS_AWAITING = 3;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%appropriation_scheme}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'animal_id' => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_ANIMAL'),
            'scheme_id' => 'Схема лечения',
            'status' => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_STATUS'),
            'started_at' => 'Дата применения схемы',
            'finished_at' => 'Дата завершения схемы',
        ];
    }

    /**
     * @return array
     */
    public static function getHealthStatusList()
    {
        return [
            self::RESULT_STATUS_HEALTHY => 'Здоровая',
            self::RESULT_STATUS_SICK => 'Больная',
            self::RESULT_STATUS_AWAITING => 'В ожидании',
        ];
    }

    /**
     * @return array
     */
    /*public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['started_at']
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }*/

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'scheme_id', 'status'], 'integer'],
            [['animal_id', 'scheme_id', 'status', 'started_at'], 'required'],
            [['started_at', 'finished_at'], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheme()
    {
        return $this->hasOne(Scheme::class, ['id' => 'scheme_id']);
    }

    /**
     *
     * @throws Exception|Throwable
     */
    public function removeFromScheme()
    {
        ActionHistory::deleteAll(['appropriation_scheme_id' => $this->id]);

        $appropriationScheme = self::findOne($this->id);

        if ($appropriationScheme) {
            $appropriationScheme->delete();
        }

        $user = Yii::$app->getUser()->getIdentity();
        $userId = ArrayHelper::getValue($user, "id");

        $userName = ArrayHelper::getValue($user, "lastName");
        $animalName = ArrayHelper::getValue($this, "animal.nickname");
        $schemeName = ArrayHelper::getValue($this, "scheme.name");

        $newAnimalHistory = new AnimalHistory([
            'animal_id' => $this->animal_id,
            'user_id' => $userId,
            'date' => (new DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
            'action_type' => AnimalHistory::ACTION_TYPE_DELETE_SCHEME,
            'action_text' => "Убрал \"$animalName\" со схемы лечения \"$schemeName\""
        ]);

        $newAnimalHistory->save();
    }

    /**
     * Создание невыполненных действий для истории
     */
    public function createActionHistory()
    {
        /** @var Scheme $scheme */
        $scheme = Scheme::find()
            ->alias('s')
            ->joinWith([
                'schemeDays' => function (ActiveQuery $query) {
                    $query->joinWith([
                        'groupsAction' => function (ActiveQuery $query) {
                            $query->joinWith(['actions']);
                        }
                    ]);
                }
            ])
            ->where([
                's.id' => $this->scheme_id
            ])->one();

        $user = Yii::$app->getUser()->getIdentity();
        $userId = ArrayHelper::getValue($user, "id");
        foreach ($scheme->schemeDays as $day) {
            foreach ($day->groupsAction as $group) {
                foreach ($group->actions as $action) {
                    $schemeDayAt = date('Y-m-d',
                        strtotime((string)$this->started_at . ' + ' . ($day->number - 1) . ' days'));

                    $newActionHistory = new ActionHistory([
                        "appropriation_scheme_id" => $this->id,
                        "scheme_day_at" => $schemeDayAt,
                        "scheme_day" => $day->number,
                        "groups_action_id" => $group->id,
                        "action_id" => $action->id,
                        "text_value" => null,
                        "number_value" => null,
                        "double_value" => null,
                        "list_value" => null,
                        "execute_at" => null,
                        "created_at" => $userId,
                        "updated_at" => $userId,
                        "status" => ActionHistory::STATUS_NEW,
                    ]);
                    $newActionHistory->save();
                }
            }
        }

        $userName = ArrayHelper::getValue($user, "lastName");
        $animalName = ArrayHelper::getValue($this, "animal.nickname");
        $schemeName = ArrayHelper::getValue($this, "scheme.name");

        /** @var AnimalHistory $newAnimalHistory */
        $newAnimalHistory = new AnimalHistory([
            'animal_id' => $this->animal_id,
            'user_id' => $userId,
            'date' => (new DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
            'action_type' => AnimalHistory::ACTION_TYPE_APPROPRIATION_SCHEME,
            'action_text' => "Поставил \"$animalName\" на схему лечения \"$schemeName\""
        ]);

        $newAnimalHistory->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionHistory()
    {
        return $this->hasMany(ActionHistory::class, ['appropriation_scheme_id' => 'id']);
    }

    /**
     * Получение списка невыполненных действий для текущего применения схемы
     * @return bool
     */
    public function getListNewActions()
    {
        return ActionHistory::find()
            ->where([
                'appropriation_scheme_id' => $this->id,
                'status'                  => ActionHistory::STATUS_NEW
            ])
            ->exists();
    }
}
