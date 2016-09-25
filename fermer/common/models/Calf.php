<?php

namespace common\models;


use yii\db\ActiveRecord;

class Calf extends ActiveRecord
{
    public function getSuit()
    {
        return $this->hasMany(Color::className(), ['id' => 'color'])->one();
    }
}