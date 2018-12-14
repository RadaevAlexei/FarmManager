<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Action
 * @package backend\modules\scheme\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $action_list_id
 * @property ActionList actionList
 */
class Action extends ActiveRecord
{
    /**
     * Какое количество действий будет выводиться на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name'           => Yii::t('app/action', 'ACTION_NAME'),
            'type'           => Yii::t('app/action', 'ACTION_TYPE'),
            'action_list_id' => Yii::t('app/action', 'ACTION_LIST'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['name', 'type'], 'required'],
            ['name', 'string', 'max' => 255],
            [['type', 'action_list_id'], 'integer']
        ];
    }

    public function getActionList()
    {
        return $this->hasOne(ActionList::class, ['id' => 'action_list_id']);
    }
}