<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class SchemeDayGroupsActionLink
 * @package backend\modules\scheme\models
 *
 * @property integer $scheme_day_id
 * @property integer $groups_action_id
 */
class SchemeDayGroupsActionLink extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%scheme_day_groups_action_link}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['scheme_day_id', 'groups_action_id'], 'required'],
            [['scheme_day_id', 'groups_action_id'], 'integer']
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
    public function getSchemeDay()
    {
        return $this->hasOne(SchemeDay::class, ['id' => 'scheme_day_id']);
    }
}