<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class ActionList
 * @package backend\modules\scheme\models
 *
 * СПИСКИ
 *
 * @property string $name
 * @property integer $type
 * @property ActionListItem $items
 */
class ActionList extends ActiveRecord
{
    /**
     * Какое количество списков будет выводиться на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action_list}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/action-list', 'ACTION_LIST_NAME'),
            'type' => Yii::t('app/action-list', 'ACTION_LIST_TYPE')
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
            ['type', 'integer'],
            ['items', 'safe'],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(ActionListItem::class, ['action_list_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }
}