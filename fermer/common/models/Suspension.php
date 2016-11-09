<?php

namespace common\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Suspension extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'calf' => 'Теленок',
            'weight' => 'Вес',
            'date' => 'Дата взвешивания'
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->calf = [
            'nickname' => ArrayHelper::getValue($this, 'calfInfo.nickname', null),
            'birthday' => ArrayHelper::getValue($this, 'calfInfo.birthday', null)
        ];
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

    public function rules()
    {
        return [
            [['calf', 'weight', 'date'], 'required'],
            ['weight', 'double'],
        ];
    }

    public function getCalfInfo()
    {
        return $this->hasOne(Calf::className(), ['number' => 'calf']);
    }
}