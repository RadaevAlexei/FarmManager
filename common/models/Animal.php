<?php

namespace common\models;

use backend\modules\reproduction\models\Insemination;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\AnimalHistory;
use backend\modules\scheme\models\AppropriationScheme;
use backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;

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
 * @property \DateTime $date_health
 * @property \DateTime $birthday
 * @property string $nickname
 * @property string $label
 * @property integer $animal_group
 * @property integer $sex
 * @property integer $physical_state
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
            'color' => 'Масть',
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
            [
                [
                    'farm_id',
                    'nickname',
                    'group_id',
                    'animal_group_id',
                    'box',
                    'color',
                    'mother_id',
                    'father_id',
                    'cowshed_id',
                    'status',
                    'rectal_examination',
                    'collar',
                    'health_status',
                    'health_status_comment',
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
                'color',
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
                'color',
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

    public function isMan()
    {
        return $this->sex === self::SEX_TYPE_MAN;
    }

    public function isWoman()
    {
        return $this->sex === self::SEX_TYPE_WOMAN;
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
            0 => Yii::t('app/animal', 'ANIMAL_BULL'),
            1 => Yii::t('app/animal', 'ANIMAL_CALF'),
            2 => Yii::t('app/animal', 'ANIMAL_CALF_PREDSLUCH'),
            3 => Yii::t('app/animal', 'ANIMAL_CALF_SLUCH'),
            4 => Yii::t('app/animal', 'ANIMAL_NEMATODE'),
            5 => Yii::t('app/animal', 'ANIMAL_FIRST_AID'),
            6 => Yii::t('app/animal', 'ANIMAL_COW')
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
     * @param AppropriationScheme $appropriationScheme
     *
     * @return array|ActiveRecord[]
     */
    public function getActionsToday($appropriationScheme)
    {
        return ActionHistory::find()
            ->joinWith(['groupsAction', 'action'])
            ->where([
                'appropriation_scheme_id' => $appropriationScheme->id,
                'scheme_day_at' => (new \DateTime('now',
                    (new \DateTimeZone('Europe/Samara'))))->format('Y-m-d'),
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
                'ac.sex',
                'ac.physical_state',
                'ac.birth_weight',
                'u.lastname',
            ])
            ->innerJoin(['cl' => CalvingLink::tableName()], 'c.id = cl.calving_id')
            ->innerJoin(['ac' => Animal::tableName()], 'cl.child_animal_id = ac.id')
            ->leftJoin(['u' => User::tableName()], 'c.user_id = u.id')
            ->andWhere(['=', 'c.animal_id', $this->id])
            ->asArray()
            ->all();
    }
}
