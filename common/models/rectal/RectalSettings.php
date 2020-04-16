<?php

namespace common\models\rectal;

use Yii;
use yii\db\ActiveRecord;

/**
 * Настройки по ректальному исследованию
 *
 * Class RectalSettings
 * @package common\models\rectal
 *
 * @property integer $pregnancy_time
 * @property integer $end_time
 * @property integer $first_day
 * @property integer $confirm_first
 * @property integer $confirm_second
 */
class RectalSettings extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%rectal_settings}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'pregnancy_time' => 'Срок стельности, дн',
            'end_time' => 'Срок запуска, дн',
            'first_day' => 'Количество дней до первого УЗИ, дн',
            'confirm_first' => 'Подтверждение стельности №1, дн',
            'confirm_second' => 'Подтверждение стельности №2, дн',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['pregnancy_time', 'end_time', 'first_day', 'confirm_first', 'confirm_second'], 'required'],
            [['pregnancy_time', 'end_time', 'first_day', 'confirm_first', 'confirm_second'], 'integer']
        ];
    }
}