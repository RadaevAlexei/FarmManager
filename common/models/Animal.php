<?php

namespace common\models;

use backend\modules\reproduction\models\Insemination;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\AnimalHistory;
use backend\modules\scheme\models\AppropriationScheme;
use backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;
use common\helpers\DateHelper;
use common\models\rectal\Rectal;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Общий класс для животных
 *
 * Class Animal
 * @package common\models
 *
 * @property integer $id
 * @property integer $collar
 * @property integer $health_status
 * @property integer $health_status_comment
 * @property integer $diagnosis
 * @property DateTime $date_health
 * @property DateTime $birthday
 * @property string $nickname
 * @property string $label
 * @property integer $animal_group
 * @property integer $sex
 * @property integer $physical_state
 * @property integer $cur_insemination_id
 * @property integer $fremartin
 * @property integer $rectal_examination
 * @property Insemination $curInsemination
 * @property integer $color_id
 */
class Animal extends ActiveRecord
{
    /**
     * Сценарий при создании и редактировании животного
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    const SEX_TYPE_MAN = 1;                 // Мужской пол
    const SEX_TYPE_WOMAN = 2;               // Женский пол

    /**
     * Физиологические состояния
     */
    const PHYSICAL_STATE_BULL = 0;            // "Бычок"
    const PHYSICAL_STATE_CALF = 1;            // "Тёлочка"
    const PHYSICAL_STATE_CALF_PREDSLUCH = 2;  // "Тёлочка предслучного возраста"
    const PHYSICAL_STATE_CALF_SLUCH = 3;      // "Тёлочка случного возраста"
    const PHYSICAL_STATE_CALF_NEMATODE = 4;   // "Нетель"
    const PHYSICAL_STATE_CALF_FIRST_AID = 5;  // "Первотелка"
    const PHYSICAL_STATE_COW = 6;             // "Корова"

    /**
     * Ректальное исследование
     */
    const RECTAL_EXAMINATION_NOT_STERILE = 0; // "Не стельная"
    const RECTAL_EXAMINATION_STERILE = 1;     // "Стельная"
    const RECTAL_EXAMINATION_DUBIOUS = 2;     // "Сомнительная"

    /**
     *
     */
    public static $AVAILABLE_RECTAL_EXAMINATION_STATUSES = [
        self::RECTAL_EXAMINATION_NOT_STERILE,
        self::RECTAL_EXAMINATION_STERILE,
        self::RECTAL_EXAMINATION_DUBIOUS
    ];

    /**
     * Статусы
     */
    const STATUS_INSEMINATED = 0;     // "Осемененная"
    const STATUS_NOT_INSEMINATED = 1; // "Не осемененная"
    const STATUS_HUNT = 2;            // "Охота"

    /**
     * Состояния здоровья
     */
    const HEALTH_STATUS_HEALTHY = 0;            // "Здоровая"
    const HEALTH_STATUS_SICK = 1;               // "Больная"
    const HEALTH_STATUS_AWAITING = 2;           // "В ожидании"
    const HEALTH_STATUS_DEAD = 3;               // Мертвый

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%animals}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cowshed_id' => 'Коровник',
            'box' => 'Бокс',
            'nickname' => 'Кличка',
            'label' => 'Бирка',
            'farm_id' => 'Происхождение',
            'birthday' => 'Дата Рождения',
            'sex' => 'Пол животного',
            'birth_weight' => 'Вес при рождении',
            'color_id' => 'Масть',
            'mother_id' => 'Мать',
            'father_id' => 'Отец',
            'group_id' => 'Группа',
            'animal_group_id' => 'Группа животного',
            'physical_state' => 'Физиологическое состояние',
            'status' => 'Статус',
            'rectal_examination' => 'Ректальное исследование',
            'previous_weighing_date' => 'Дата предыдущего взвешивания',
            'previous_weighing' => 'Предыдущее взвешивание',
            'current_weighing_date' => 'Дата текущего взвешивания',
            'current_weighing' => 'Текущее взвешивание',
            'collar' => 'Номер ошейника',
            'health_status' => 'Состояние здоровья',
            'health_status_comment' => 'Комментарий',
            'diagnosis' => 'Диагноз',
            'cur_insemination_id' => 'Текущее осеменение',
            'fremartin' => 'Фримартин',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['label', 'unique', 'on' => self::SCENARIO_CREATE_EDIT],
            [
                [
                    'label',
//                    'nickname',
                    'birthday',
                    'sex',
                    'physical_state'
                ],
                'required',
                'on' => self::SCENARIO_CREATE_EDIT
            ],
            ['birth_weight', 'double'],
            ['cur_insemination_id', 'integer'],
            [
                [
                    'farm_id',
                    'nickname',
                    'group_id',
                    'animal_group_id',
                    'box',
                    'color_id',
                    'mother_id',
                    'father_id',
                    'cowshed_id',
                    'status',
                    'rectal_examination',
                    'collar',
                    'health_status',
                    'health_status_comment',
                    'cur_insemination_id',
                    'fremartin',
                ],
                'safe'
            ],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'cowshed_id',
                'box',
                'nickname',
                'label',
                'farm_id',
                'birthday',
                'sex',
                'birth_weight',
                'color_id',
                'mother_id',
                'father_id',
                'group_id',
                'animal_group_id',
                'physical_state',
                'status',
                'rectal_examination',
                'previous_weighing_date',
                'previous_weighing',
                'current_weighing_date',
                'current_weighing',
                'collar',
            ],
            self::SCENARIO_FILTER => [
                'cowshed_id',
                'box',
                'nickname',
                'label',
                'farm_id',
                'birthday',
                'sex',
                'birth_weight',
                'color_id',
                'mother_id',
                'father_id',
                'group_id',
                'animal_group_id',
                'physical_state',
                'status',
                'rectal_examination',
                'previous_weighing_date',
                'previous_weighing',
                'current_weighing_date',
                'current_weighing',
                'collar',
            ]
        ];
    }

    /**
     * @return array
     */
    public static function getListRectalStatuses()
    {
        return [
            self::RECTAL_EXAMINATION_NOT_STERILE => 'Не стельная',
            self::RECTAL_EXAMINATION_STERILE => 'Стельная',
            self::RECTAL_EXAMINATION_DUBIOUS => 'Сомнительная'
        ];
    }

    /**
     * @param $status
     * @return mixed
     */
    public static function getRectalStatusLabel($status)
    {
        return self::getListRectalStatuses()[$status];
    }

    public function isMan()
    {
        return $this->sex === self::SEX_TYPE_MAN;
    }

    public function isWoman()
    {
        return $this->sex === self::SEX_TYPE_WOMAN;
    }

    /**
     * Вычисление возраста животного
     *
     * @return string
     * @throws Exception
     */
    public function getAge()
    {
        $birthday = new DateTime($this->birthday);
        $birthday->setTime(0, 0, 0);

        $nowDate = new DateTime();
        $nowDate->setTime(0, 0, 0);

        $diff = $birthday->diff($nowDate);
        $diff->h = 0;
        $diff->m = 0;
        $diff->s = 0;

        return Yii::$app->formatter->asDuration($diff);
    }

    /**
     * Проверяем, является ли корова стельной
     * @return bool
     */
    public function isSterile()
    {
        return ($this->sex == self::SEX_TYPE_WOMAN) &&
            ($this->rectal_examination === self::RECTAL_EXAMINATION_STERILE);
    }

    /**
     * Проверяем, является ли корова не стельной
     * @return bool
     */
    public function isNotSterile()
    {
        return ($this->sex == self::SEX_TYPE_WOMAN) &&
            ($this->rectal_examination === self::RECTAL_EXAMINATION_NOT_STERILE);
    }

    /**
     * Является ли сомнительной после РИ
     * @return bool
     */
    public function isDubious()
    {
        return ($this->sex == self::SEX_TYPE_WOMAN) &&
            ($this->rectal_examination === self::RECTAL_EXAMINATION_DUBIOUS);
    }

    /**
     * Список полов животных
     *
     * @return array
     */
    public static function getListSexTypes()
    {
        return [
            1 => Yii::t('app/animal', 'ANIMAL_MAN'),
            2 => Yii::t('app/animal', 'ANIMAL_WOMAN')
        ];
    }

    /**
     * @return array
     */
    public static function getHealthStatusList()
    {
        return [
            self::HEALTH_STATUS_HEALTHY => 'Здоровая',
            self::HEALTH_STATUS_SICK => 'Больная',
            self::HEALTH_STATUS_AWAITING => 'В ожидании',
        ];
    }

    /**
     * @return mixed
     */
    public function getHealthStatus()
    {
        return self::getHealthStatusList()[$this->health_status];
    }

    /**
     * Проверяем стоит ли животное на схеме
     *
     * @return array|ActiveRecord[]
     */
    public function onScheme()
    {
        return AppropriationScheme::find()
            ->alias('as')
            ->joinWith([
                'scheme' => function (ActiveQuery $query) {
                    $query->alias('s');
                    $query->where(['s.status' => Scheme::STATUS_ACTIVE]);
                }
            ])
            ->where([
                'animal_id' => $this->id,
                'as.status' => AppropriationScheme::STATUS_IN_PROGRESS,
            ])
            ->all();
    }

    /**
     * Получение пола животного по значению
     *
     * @param $value
     *
     * @return mixed
     */
    public static function getSexType($value)
    {
        return self::getListSexTypes()[$value];
    }

    /**
     * Физиологические состояния
     *
     * @return array
     */
    public static function getListPhysicalState()
    {
        return [
            self::PHYSICAL_STATE_BULL => Yii::t('app/animal', 'ANIMAL_BULL'),
            self::PHYSICAL_STATE_CALF => Yii::t('app/animal', 'ANIMAL_CALF'),
            self::PHYSICAL_STATE_CALF_PREDSLUCH => Yii::t('app/animal', 'ANIMAL_CALF_PREDSLUCH'),
            self::PHYSICAL_STATE_CALF_SLUCH => Yii::t('app/animal', 'ANIMAL_CALF_SLUCH'),
            self::PHYSICAL_STATE_CALF_NEMATODE => Yii::t('app/animal', 'ANIMAL_NEMATODE'),
            self::PHYSICAL_STATE_CALF_FIRST_AID => Yii::t('app/animal', 'ANIMAL_FIRST_AID'),
            self::PHYSICAL_STATE_COW => Yii::t('app/animal', 'ANIMAL_COW'),
        ];
    }

    /**
     * Получение физиологического состояния животного по значению
     *
     * @param $value
     *
     * @return mixed
     */
    public static function getPhysicalState($value)
    {
        return self::getListPhysicalState()[$value];
    }

    /**
     * Список статусов
     *
     * @return array
     */
    public static function getListStatuses()
    {
        return [
            self::STATUS_INSEMINATED => Yii::t('app/animal', 'ANIMAL_STATUS_INSEMINATED'),
            self::STATUS_NOT_INSEMINATED => Yii::t('app/animal', 'ANIMAL_STATUS_NOT_INSEMINATED'),
            self::STATUS_HUNT => Yii::t('app/animal', 'ANIMAL_STATUS_HUNT'),
        ];
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function getStatus($value)
    {
        return self::getListStatuses()[$value];
    }

    /**
     * Список возможных ректальных исследований
     *
     * @return array
     */
    public static function getListRectalExaminations()
    {
        return [
            self::RECTAL_EXAMINATION_NOT_STERILE => Yii::t('app/animal', 'ANIMAL_NOT_STERILE'),
            self::RECTAL_EXAMINATION_STERILE => Yii::t('app/animal', 'ANIMAL_STERILE'),
            self::RECTAL_EXAMINATION_DUBIOUS => Yii::t('app/animal', 'ANIMAL_DUBIOUS'),
        ];
    }

    /**
     *
     * @param $value
     *
     * @return mixed
     */
    public static function getRectalExamination($value)
    {
        return self::getListRectalExaminations()[$value];
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function getAllListAnimals()
    {
        return Animal::find()->select(['label', 'id'])->indexBy('id')->column();
    }


    /**
     * @param bool $insert
     *
     * @return bool
     */
    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $birthdayStr = $this->birthday;

            $this->birthday = strtotime($this->birthday);
            $this->previousWeighingDate = $this->birthday;
            $this->currentWeighingDate = $this->birthday;
            $this->previousWeighing = $this->birthWeight;
            $this->currentWeighing = $this->birthWeight;

            $suspensionModel = new Suspension();

            $suspensionModel->setAttributes([
                'calf'   => $this->number,
                'weight' => $this->birthWeight,
                'date'   => $birthdayStr
            ]);

            if ($suspensionModel->validate()) {
                $suspensionModel->save();
            }

            return true;
        } else {
            return false;
        }
    }*/

    /**
     * Группа
     *
     * @return Yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    /**
     * Группа животного
     *
     * @return Yii\db\ActiveQuery
     */
    public function getAnimalGroup()
    {
        return $this->hasOne(AnimalGroup::class, ['id' => 'animal_group_id']);
    }

    /**
     * Масть животного
     *
     * @return Yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::class, ['id' => 'color_id']);
    }

    /**
     * Коровник
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCowshed()
    {
        return $this->hasOne(Cowshed::class, ['id' => 'cowshed_id']);
    }

    /**
     * Ферма
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    /**
     * @param $appropriationScheme
     * @return array|ActiveRecord[]
     * @throws Exception
     */
    public function getActionsToday($appropriationScheme)
    {
        return ActionHistory::find()
            ->joinWith(['groupsAction', 'action'])
            ->where([
                'appropriation_scheme_id' => $appropriationScheme->id,
                'scheme_day_at' => (new DateTime('now',
                    (new DateTimeZone('Europe/Samara'))))->format('Y-m-d'),
                'status' => ActionHistory::STATUS_NEW
            ])
            ->all();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getListSchemes()
    {
        if (empty($this->diagnosis)) {
            return [];
        }

        return Scheme::find()
            ->where(['diagnosis_id' => $this->diagnosis])
            ->andWhere(['approve' => Scheme::APPROVED])
            ->andWhere(['status' => Scheme::STATUS_ACTIVE])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppropriationScheme()
    {
        return $this->hasOne(AppropriationScheme::class, ['animal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnoses()
    {
        return $this->hasOne(Diagnosis::class, ['id' => 'diagnosis']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCurInsemination()
    {
        return $this->hasOne(Insemination::class, ['id' => 'cur_insemination_id']);
    }

    /**
     * Проверяем, здоровое ли у нас животное
     * @return bool
     */
    public function isHealthy()
    {
        return $this->health_status === self::HEALTH_STATUS_HEALTHY;
    }

    /**
     * Проверяем, больное ли у нас животное
     * @return bool
     */
    public function isSick()
    {
        return $this->health_status === self::HEALTH_STATUS_SICK;
    }

    /**
     * Амбулаторная карта животного
     *
     * @return array|ActiveRecord[]
     */
    public function getHistory()
    {
        return AnimalHistory::find()
            ->where(['animal_id' => $this->id])
            ->orderBy(['date' => SORT_DESC])
            ->all();
    }

    /**
     * Заметки оставленные пользователями на детальной страничке животного
     * @return array|ActiveRecord[]
     */
    public function getNotes()
    {
        return AnimalNote::find()
            ->where(['animal_id' => $this->id])
            ->orderBy(['date' => SORT_DESC])
            ->all();
    }

    /**
     * Получение истории осеменений животного
     *
     * @return array|ActiveRecord[]
     */
    public function getInseminations()
    {
        return Insemination::find()
            ->where(['animal_id' => $this->id])
            ->orderBy(['date' => SORT_DESC])
            ->all();
    }

    public function getCalvings()
    {
        return Calving::find()
            ->alias('c')
            ->select([
                'c.date',
                'c.status',
                'c.position',
                'c.note',
                'cl.id',
                'cl.calving_id',
                'cl.child_animal_id',
                'ac.label',
                'ac.birthday',
                'ac.fremartin',
                'ac.health_status',
                'ac.sex',
                'ac.physical_state',
                'ac.birth_weight',
                'u.lastname',
            ])
            ->innerJoin(['cl' => CalvingLink::tableName()], 'c.id = cl.calving_id')
            ->innerJoin(['ac' => Animal::tableName()], 'cl.child_animal_id = ac.id')
            ->leftJoin(['u' => User::tableName()], 'c.user_id = u.id')
            ->andWhere(['=', 'c.animal_id', $this->id])
            ->orderBy(['c.date' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * История всех ректальных исследований
     * @return array|ActiveRecord[]
     */
    public function rectalHistory()
    {
        return Rectal::find()
            ->alias('r')
            ->select(['r.*', 'u.lastName'])
            ->leftJoin(['u' => User::tableName()], 'r.user_id = u.id')
            ->where(['=', 'r.animal_id', $this->id])
            ->orderBy(['r.date' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * Изменение текущего осеменения
     *
     * @param $newInseminationId
     */
    public function changeCurInsemination($newInseminationId)
    {
        $this->updateAttributes(['cur_insemination_id' => $newInseminationId]);
    }

    /**
     * Сброс текущего осеменения
     */
    public function resetCurInsemination()
    {
        $this->updateAttributes(['cur_insemination_id' => null]);
    }

    /**
     * Получаем данные для добавления РИ
     * @return array
     * @throws Exception
     */
    public function getAddRectalData()
    {
        $curInsemination = Insemination::findOne($this->cur_insemination_id);

        if (!$curInsemination) {
            return [
                'disable' => true,
                'can-insemination' => true,
                'stage' => []
            ];
        }

        $curStage = $curInsemination->getCurStage();
        if (!$curStage) {
            return [
                'disable' => true,
                'can-insemination' => true,
                'stage' => []
            ];
        }

        /** @var Rectal $curRectal */
        $curRectal = Rectal::findOne(ArrayHelper::getValue($curStage, "rectal_id"));
        if (!$curRectal ||
            !in_array($curRectal->result, [
                Rectal::RESULT_NOT_RESULT,
                Rectal::RESULT_DUBIOUS
            ])
        ) {
            return [
                'disable' => true,
                'can-insemination' => true,
                'stage' => []
            ];
        }

//        $curDate = (new DateTime('now', new DateTimeZone('Europe/Samara')))->setTime(0, 0);
        $curDate = (new DateTime('2021-09-03'))->setTime(0, 0);
        $rectalDate = (new DateTime(ArrayHelper::getValue($curStage, "rectal_date")))->setTime(0, 0);
        $stage = ArrayHelper::getValue($curStage, 'rectal_stage');
        $result = ArrayHelper::getValue($curStage, 'result');

        return [
            'disable' => ($curRectal->result == Rectal::RESULT_DUBIOUS) ? false : $curDate < $rectalDate,
            'can-insemination' => $this->isNotSterile() && ($stage == 1) && ($result == Rectal::RESULT_NOT_RESULT),
            'stage' => $curStage
        ];
    }

    /**
     * @param $newStatus
     */
    public function updateRectalStatus($newStatus)
    {
        $this->updateAttributes(['rectal_examination' => $newStatus]);
    }

    /**
     * Количество дней стельности
     * @return int|mixed
     * @throws Exception
     */
    public function getCountSterileDays()
    {
//        if (!$this->isSterile()) {
//            return 0;
//        }

        $curInsemination = $this->curInsemination;
//        if (!$curInsemination || ($curInsemination->status != Insemination::STATUS_SEMINAL)) {
        if (!$curInsemination) {
            return 0;
        }

        $curDate = (new DateTime('2020-05-09', (new DateTimeZone('Europe/Samara'))));
        $dateInsemination = (new DateTime(
            ArrayHelper::getValue($curInsemination, "date"),
            (new DateTimeZone('Europe/Samara'))
        ));

        return DateHelper::diff($curDate, $dateInsemination);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function canAddCalving()
    {
        return $this->isSterile() && ($this->getCountSterileDays() >= 250);
    }

    /**
     * Получение сервис-периода у коровы
     * @return int|mixed
     * @throws Exception
     */
    public function getServicePeriod()
    {
        $calvings = $this->calvings;

        if (!$calvings || !$this->isSterile()) {
            return 0;
        }

        $calvingDate = new DateTime(
            ArrayHelper::getValue($calvings[0], "date"),
            new DateTimeZone('Europe/Samara')
        );
        $inseminationDate = new DateTime(
            ArrayHelper::getValue($this, "curInsemination.date"),
            new DateTimeZone('Europe/Samara')
        );

        if (!$calvingDate || !$inseminationDate) {
            return 0;
        }

        return DateHelper::diff($inseminationDate, $calvingDate);
    }
}
