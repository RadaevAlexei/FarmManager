<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * Class Calf
 * @package common\models
 *
 * @property string number
 * @property string nickname
 * @property integer groupId
 * @property integer birthday
 * @property integer gender
 * @property double birthWeight
 * @property integer previousWeighingDate
 * @property double previousWeighing
 * @property integer currentWeighingDate
 * @property double currentWeighing
 * @property integer color
 * @property integer motherId
 * @property integer fatherId
 */
class Calf extends ActiveRecord
{
    /**
     * Количество голов на странице
     */
    const PAGE_SIZE = 20;

    /**
     * Сценарий при создании и редактировании головы
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuit()
    {
        return $this->hasOne(Color::className(), ['id' => 'color']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalfGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'groupId']);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'number'               => 'Уникальный номер',
            'nickname'             => 'Кличка',
            'groupId'              => 'Группа',
            'birthday'             => 'Дата Рождения',
            'gender'               => 'Пол',
            'birthWeight'          => 'Вес при рождении',
            'previousWeighingDate' => 'Дата предыдущего взвешивания',
            'previousWeighing'     => 'Предыдущее взвешивание',
            'currentWeighingDate'  => 'Дата текущего взвешивания',
            'currentWeighing'      => 'Текущее взвешивание',
            'color'                => 'Масть',
            'motherId'             => 'Мать',
            'fatherId'             => 'Отец',
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%calf}}';
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'number',
                'nickname',
                'groupId',
                'birthday',
                'gender',
                'birthWeight',
                'previousWeighingDate',
                'previousWeighing',
                'currentWeighingDate',
                'currentWeighing',
                'color',
                'motherId',
                'fatherId',
            ],
            self::SCENARIO_FILTER      => [
                'number',
                'nickname',
                'groupId',
                'birthday',
                'gender',
                'birthWeight',
                'previousWeighingDate',
                'previousWeighing',
                'currentWeighingDate',
                'currentWeighing',
                'color',
                'motherId',
                'fatherId',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
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
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['number', 'unique'],
            [
                [
                    'number',
                    'birthday',
                    'gender',
                    'birthWeight'
                ],
                'required',
                'on' => self::SCENARIO_CREATE_EDIT
            ],
            ['birthWeight', 'double'],
            [
                [
                    'nickname',
                    'groupId',
                    'previousWeighingDate',
                    'previousWeighing',
                    'currentWeighingDate',
                    'currentWeighing',
                    'color',
                    'motherId',
                    'fatherId',
                ],
                'safe'
            ],
        ];
    }
}