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
}