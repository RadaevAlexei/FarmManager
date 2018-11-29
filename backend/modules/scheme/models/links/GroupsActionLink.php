<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class GroupsActionLink
 * @package backend\modules\scheme\models
 *
 * @property integer $groups_action_id
 * @property integer $action_id
 */
class GroupsActionLink extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%groups_action_link}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['groups_action_id', 'action_id'], 'required'],
            [['groups_action_id', 'action_id'], 'integer']
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