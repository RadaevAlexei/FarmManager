<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * ПУНКТЫ СПИСКОВ
 *
 * Class ActionListItem
 * @package backend\modules\scheme\models
 *
 * @property integer $action_list_id
 * @property string $name
 * @property string $value
 * @property integer $sort
 */
class ActionListItem extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action_list_item}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'action_list_id' => Yii::t('app/action-list-item', 'ACTION_LIST_ITEM_LIST'),
            'name'           => Yii::t('app/action-list-item', 'ACTION_LIST_ITEM_NAME'),
            'value'          => Yii::t('app/action-list-item', 'ACTION_LIST_ITEM_VALUE'),
            'sort'           => Yii::t('app/action-list-item', 'ACTION_LIST_ITEM_SORT'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['action_list_id', 'name', 'value', 'sort'], 'required'],
            [['name', 'value'], 'string'],
            [['action_list', 'sort'], 'integer']
        ];
    }
}