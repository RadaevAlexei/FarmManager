<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class ActionValue
 * @package backend\modules\scheme\models
 *
 * ЗНАЧЕНИЯ ДЕЙСТВИЙ
 *
 * @property integer $scheme_id
 * @property integer $scheme_day_id
 * @property integer $groups_action_id
 * @property integer $action_id
 * @property string $text_value
 * @property integer $number_value
 * @property double $double_value
 * @property string $action_list_values
 */
class ActionValue extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action_value}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'scheme_id'          => Yii::t('app/action-value', 'ACTION_VALUE_SCHEME'),
            'scheme_day_id'      => Yii::t('app/action-value', 'ACTION_VALUE_DAY'),
            'groups_action_id'   => Yii::t('app/action-value', 'ACTION_VALUE_GROUPS_ACTION'),
            'action_id'          => Yii::t('app/action-value', 'ACTION_VALUE_ACTION'),
            'text_value'         => Yii::t('app/action-value', 'ACTION_VALUE_TEXT_VALUE'),
            'number_value'       => Yii::t('app/action-value', 'ACTION_VALUE_NUMBER_VALUE'),
            'double_value'       => Yii::t('app/action-value', 'ACTION_VALUE_DOUBLE_VALUE'),
            'action_list_values' => Yii::t('app/action-value', 'ACTION_VALUE_LIST_VALUES'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['scheme_id', 'scheme_day_id', 'groups_action_id', 'action_id'], 'required'],
            [['text_value', 'action_list_values'], 'string'],
            [['scheme_id', 'scheme_day_id', 'groups_action_id', 'action_id', 'number_value'], 'integer'],
            [['double_value'], 'double']
        ];
    }
}