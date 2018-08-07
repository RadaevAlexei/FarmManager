<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class Transfer
 * @package common\models
 *
 * @property integer $groupFromId
 * @property integer $groupToId
 * @property integer $date
 * @property integer $calf
 */
class Transfer extends ActiveRecord
{
    /**
     * Количество переводов на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании перевод
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['groupFromId', 'groupToId', 'date', 'calf'], 'required', 'on' => self::SCENARIO_CREATE_EDIT],
            [['groupFromId', 'groupToId', 'calf'], 'integer', 'on' => self::SCENARIO_CREATE_EDIT]
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%transfer}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupFrom()
    {
        return $this->hasOne(Group::className(), ['id' => 'groupFromId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTo()
    {
        return $this->hasOne(Group::className(), ['id' => 'groupToId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalfInfo()
    {
        return $this->hasOne(Cow::className(), ['number' => 'calf']);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'groupFromId' => 'Откуда',
            'groupToId'   => 'Куда',
            'date'        => 'Дата',
            'calf'        => 'Телёнок'
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date = strtotime($this->date);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'groupFromId',
                'groupToId',
                'date',
                'calf',
            ],
            self::SCENARIO_FILTER      => [
                'groupFromId',
                'groupToId',
                'date',
                'calf',
            ],
        ];
    }
}