<?php

namespace backend\modules\scheme\models;

use Yii;
use backend\modules\scheme\models\links\GroupsActionLink;
use yii\db\ActiveRecord;

/**
 * Class GroupsAction
 * @package backend\modules\scheme\models
 *
 * @property string $name
 * @property integer $id
 * @property Action[] $actions
 */
class GroupsAction extends ActiveRecord
{
    /**
     * Какое количество групп будет выводиться на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%groups_action}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/groups-action', 'GROUPS_ACTION_NAME')
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 255]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Action::class, ['id' => 'action_id'])
            ->viaTable(GroupsActionLink::tableName(), ['groups_action_id' => 'id']);
    }
}