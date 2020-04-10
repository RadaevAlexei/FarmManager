<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class CalvingLink - связочная таблица отёлов и приплода
 * @package common\models
 *
 * @property integer $calving_id
 * @property integer $child_animal_id
 */
class CalvingLink extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%calving_links}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'calving_id' => 'ID Отёла',
            'child_animal_id' => 'Приплод',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['calving_id', 'child_animal_id'], 'required'],
            [['calving_id', 'child_animal_id'], 'integer'],
        ];
    }


}