<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class GroupsAction
 * @package backend\modules\scheme\models
 *
 * @property string $name
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
}