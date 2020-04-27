<?php

namespace common\models\rectal;

use DateInterval;
use DateTime;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
            'end_time'       => 'Срок запуска, дн',
            'first_day'      => 'Количество дней до первого УЗИ, дн',
            'confirm_first'  => 'Подтверждение стельности №1, дн',
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

    /**
     * Получение значения по ключу
     * @param $key
     * @return mixed|null
     */
    public static function getValue($key)
    {
        $setting = self::find()->one();

        if (!$setting) {
            return null;
        }

        return ArrayHelper::getValue($setting, $key);
    }

    /**
     * Расчет даты ректального исследования от даты осеменения,
     * в зависимости от этапа
     * @param $dateFrom
     * @param int $stage
     * @return string
     * @throws Exception
     */
    public static function calculateRectalDate($dateFrom, $stage = 1)
    {
        $countDays = 0;
        $settings = self::find()->one();

        switch ($stage) {
            case Rectal::STAGE_FIRST:
                $countDays = ArrayHelper::getValue($settings, 'first_day');
                break;
            case Rectal::STAGE_CONFIRM_FIRST:
                $countDays = ArrayHelper::getValue($settings, 'confirm_first') - ArrayHelper::getValue($settings, 'first_day');
                break;
            case Rectal::STAGE_CONFIRM_SECOND:
                $countDays = ArrayHelper::getValue($settings, 'confirm_second') - ArrayHelper::getValue($settings, 'confirm_first');
                break;
        }

        return (new DateTime($dateFrom))
            ->add(new DateInterval("P${countDays}D"))
            ->format('Y-m-d H:i:s');
    }
}