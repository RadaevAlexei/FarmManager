<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Общий класс для животных
 *
 * Class Animal
 * @package common\models
 *
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
    const AVAILABLE_RECTAL_EXAMINATION_STATUSES = [
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
     * @return string
     */
    public static function tableName()
    {
        return '{{%animals}}';
    }

    /**
     * @return array
     */
    /*public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class'                     => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
            ],
        ];
    }*/

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'                     => 'ID',
            'cowshed_id'             => 'Коровник',
            'box'                    => 'Бокс',
            'nickname'               => 'Кличка',
            'label'                  => 'Бирка',
            'farm_id'                => 'Происхождение',
            'birthday'               => 'Дата Рождения',
            'sex'                    => 'Пол животного',
            'birth_weight'           => 'Вес при рождении',
            'color'                  => 'Масть',
            'mother_id'              => 'Мать',
            'father_id'              => 'Отец',
            'group_id'               => 'Группа',
            'physical_state'         => 'Физиологическое состояние',
            'status'                 => 'Статус',
            'rectal_examination'     => 'Ректальное исследование',
            'previous_weighing_date' => 'Дата предыдущего взвешивания',
            'previous_weighing'      => 'Предыдущее взвешивание',
            'current_weighing_date'  => 'Дата текущего взвешивания',
            'current_weighing'       => 'Текущее взвешивание',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['label', 'unique'],
            [
                [
                    'label',
                    'nickname',
                    'birthday',
                    'sex',
                    'physical_state'
                ],
                'required',
            ],
            ['birth_weight', 'double'],
            [
                [
                    'farm_id',
                    'nickname',
                    'group_id',
                    'box',
                    'color',
                    'mother_id',
                    'father_id',
                    'cowshed_id',
                    'status',
                    'rectal_examination',
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
                'physical_state',
                'status',
                'rectal_examination',
                'previous_weighing_date',
                'previous_weighing',
                'current_weighing_date',
                'current_weighing',
            ],
            self::SCENARIO_FILTER      => [
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
                'physical_state',
                'status',
                'rectal_examination',
                'previous_weighing_date',
                'previous_weighing',
                'current_weighing_date',
                'current_weighing',
            ]
        ];
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
     * Получение пола животного по значению
     *
     * @param $value
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
            self::STATUS_INSEMINATED     => Yii::t('app/animal', 'ANIMAL_STATUS_INSEMINATED'),
            self::STATUS_NOT_INSEMINATED => Yii::t('app/animal', 'ANIMAL_STATUS_NOT_INSEMINATED'),
            self::STATUS_HUNT            => Yii::t('app/animal', 'ANIMAL_STATUS_HUNT'),
        ];
    }

    /**
     * @param $value
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
            self::RECTAL_EXAMINATION_STERILE     => Yii::t('app/animal', 'ANIMAL_STERILE'),
            self::RECTAL_EXAMINATION_DUBIOUS     => Yii::t('app/animal', 'ANIMAL_DUBIOUS'),
        ];
    }

    /**
     *
     * @param $value
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
     * Группа животного
     *
     * @return Yii\db\ActiveQuery
     */
    public function getAnimalGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
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
}