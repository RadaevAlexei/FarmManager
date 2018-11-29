<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class SchemeDay
 * @package backend\modules\scheme\models
 *
 * @property integer $number
 * @property \DateTime $date
 */
class SchemeDay extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%scheme_day}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'number' => Yii::t('app/scheme-day', 'SCHEME_DAY_NUMBER'),
            'date'   => Yii::t('app/scheme-day', 'SCHEME_DAY_DATE')
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['number', 'date'], 'required'],
            ['number', 'integer'],
            ['date', 'datetime'],
        ];
    }
}