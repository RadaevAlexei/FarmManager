<?php

namespace common\models;


use yii\db\ActiveRecord;

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

    public function getCalfInfo()
    {
        return $this->hasOne(Calf::className(), ['id' => 'calf'])->one();
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
}