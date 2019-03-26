<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class ActionHistory
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
class ActionHistory extends ActiveRecord
{
    const STATUS_NEW = 'new';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action_history}}';
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
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['appropriation_scheme_id', 'groups_action_id', 'action_id'], 'required'],
            [['text_value'], 'string'],
            [['appropriation_scheme_id', 'groups_action_id', 'action_id', 'number_value'], 'integer'],
            [['double_value'], 'double'],
            [['scheme_day_at', 'execute_at'], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupsAction()
    {
        return $this->hasOne(GroupsAction::class, ['id' => 'groups_action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Action::class, ['id' => 'action_id']);
    }
}