<?php

namespace backend\modules\scheme\models;

use backend\modules\scheme\models\links\SchemeDayGroupsActionLink;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class SchemeDay
 * @package backend\modules\scheme\models
 *
 * @property integer $id
 * @property integer $number
 * @property GroupsAction[] $groupsAction
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
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['number'], 'required'],
            ['number', 'integer']
        ];
    }

    /**
     * Получение списка групп действий для конкретного дня в схеме
     * @return \yii\db\ActiveQuery
     */
    public function getGroupsAction()
    {
        return $this->hasMany(GroupsAction::class, ['id' => 'groups_action_id'])
            ->viaTable(SchemeDayGroupsActionLink::tableName(), ['scheme_day_id' => 'id']);
    }
}