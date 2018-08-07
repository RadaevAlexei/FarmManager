<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Suspension
 * @package common\models
 *
 * @property $calf integer
 * @property $weight double
 * @property $date integer
 */
class Suspension extends ActiveRecord
{
    /**
     * Количество перевесок на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании перевески
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'calf'   => 'Теленок',
            'weight' => 'Вес',
            'date'   => 'Дата взвешивания'
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%suspension}}';
    }

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();
        /*$this->calf = [
            'nickname' => ArrayHelper::getValue($this, 'calfInfo.nickname'),
            'birthday' => ArrayHelper::getValue($this, 'calfInfo.birthday')
        ];*/
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->date = strtotime($this->date);
        if (parent::beforeSave($insert)) {
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
            [['calf', 'weight'], 'trim'],
            [['calf', 'weight', 'date'], 'required', 'on' => self::SCENARIO_CREATE_EDIT],
            ['weight', 'double', 'on' => self::SCENARIO_CREATE_EDIT],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalfInfo()
    {
        return $this->hasOne(Calf::className(), ['number' => 'calf']);
    }
}